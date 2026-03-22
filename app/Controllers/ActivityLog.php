<?php

namespace App\Controllers;

use App\Models\UserActivityModel;
use App\Models\UserModel;

class ActivityLog extends BaseController
{
    protected UserActivityModel $activityModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->activityModel = model(UserActivityModel::class);
        $this->userModel     = model(UserModel::class);
    }

    /**
     * List user activities with optional search and sort.
     */
    public function index(): string
    {
        $q = $this->request->getGet('q');
        $sortCol   = $this->request->getGet('sort');
        $sortOrder = strtolower((string) $this->request->getGet('order')) === 'desc' ? 'desc' : 'asc';
        $allowedSort = ['created_at', 'user_name', 'user_email', 'module', 'action', 'entity_id', 'description'];
        $builder = $this->activityModel->builder()
            ->select('user_activities.*, users.name AS user_name, users.email AS user_email')
            ->join('users', 'users.id = user_activities.user_id', 'left');

        if ($q !== null && $q !== '') {
            $builder->groupStart()
                ->like('user_activities.module', $q)
                ->orLike('user_activities.action', $q)
                ->orLike('user_activities.description', $q)
                ->orLike('users.name', $q)
                ->orLike('users.email', $q)
                ->groupEnd();
        }

        if ($sortCol !== null && in_array($sortCol, $allowedSort, true)) {
            $orderCol = match ($sortCol) {
                'user_name' => 'users.name',
                'user_email' => 'users.email',
                default => 'user_activities.' . $sortCol,
            };
            $builder->orderBy($orderCol, $sortOrder);
        } else {
            $builder->orderBy('user_activities.created_at', 'desc');
        }
        $activities = $builder->get()->getResultArray();

        return view('layout/main', [
            'pageTitle' => 'Activity Log - Gameshala ERP',
            'content'   => view('admin/activity_log/index', [
                'activities' => $activities,
                'searchQ'    => $q ?? '',
                'sort'       => $sortCol ?? 'created_at',
                'order'      => $sortCol !== null && in_array($sortCol, $allowedSort, true) ? $sortOrder : 'desc',
            ]),
        ]);
    }
}
