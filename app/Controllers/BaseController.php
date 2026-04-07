<?php

namespace App\Controllers;

use App\Models\UserActivityModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        $this->helpers = ['url', 'asset', 'gaming'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    /**
     * Record a user activity for the current logged-in user.
     * No-op if no user is logged in (e.g. failed login).
     *
     * @param string      $module      e.g. 'auth', 'catalog', 'inventory'
     * @param string      $action      e.g. 'login', 'vendor_create', 'product_update'
     * @param int|null    $entityId    Optional ID of the affected record (vendor id, product id, etc.)
     * @param string|null $description Optional human-readable description (max 255 chars)
     */
    protected function logActivity(string $module, string $action, ?int $entityId = null, ?string $description = null): void
    {
        $userId = session('user_id');
        if ($userId === null) {
            return;
        }
        $model = model(UserActivityModel::class);
        $model->insert([
            'user_id'     => (int) $userId,
            'module'      => $module,
            'action'      => $action,
            'entity_id'   => $entityId,
            'description' => $description !== null && strlen($description) > 255 ? substr($description, 0, 252) . '...' : $description,
        ]);
    }
}
