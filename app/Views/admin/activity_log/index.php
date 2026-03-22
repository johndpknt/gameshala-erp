<?php
$logBase = base_url('admin/activity-log');
$logSortUrl = function ($col) use ($logBase, $searchQ, $sort, $order) {
    $next = ($sort === $col && $order === 'asc') ? 'desc' : 'asc';
    return $logBase . '?' . http_build_query(array_filter(['q' => $searchQ ?? '', 'sort' => $col, 'order' => $next]));
};
$logArrow = function ($col) use ($sort, $order) {
    if ($sort !== $col) return '';
    return $order === 'asc' ? ' ↑' : ' ↓';
};
?>
<div class="container py-4 px-3 px-sm-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h1 class="h4 fw-semibold mb-0">Activity Log</h1>
    </div>

    <form method="get" action="<?= base_url('admin/activity-log') ?>" class="mb-4">
        <div class="input-group" style="max-width: 450px;">
            <input type="search" name="q" class="form-control" placeholder="Search by module, action, description, user name or email..."
                   value="<?= esc($searchQ ?? '') ?>">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th><a href="<?= $logSortUrl('created_at') ?>" class="text-dark text-decoration-none">Date / Time<?= $logArrow('created_at') ?></a></th>
                    <th><a href="<?= $logSortUrl('user_name') ?>" class="text-dark text-decoration-none">User<?= $logArrow('user_name') ?></a></th>
                    <th><a href="<?= $logSortUrl('module') ?>" class="text-dark text-decoration-none">Module<?= $logArrow('module') ?></a></th>
                    <th><a href="<?= $logSortUrl('action') ?>" class="text-dark text-decoration-none">Action<?= $logArrow('action') ?></a></th>
                    <th><a href="<?= $logSortUrl('entity_id') ?>" class="text-dark text-decoration-none">Entity ID<?= $logArrow('entity_id') ?></a></th>
                    <th><a href="<?= $logSortUrl('description') ?>" class="text-dark text-decoration-none">Description<?= $logArrow('description') ?></a></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($activities)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">No activity found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($activities as $a): ?>
                        <tr>
                            <td class="text-nowrap small"><?= esc($a['created_at'] ?? '—') ?></td>
                            <td>
                                <?= esc($a['user_name'] ?? '—') ?>
                                <?php if (! empty($a['user_email'])): ?>
                                    <br><span class="small text-muted"><?= esc($a['user_email']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><code class="small"><?= esc($a['module'] ?? '—') ?></code></td>
                            <td><code class="small"><?= esc($a['action'] ?? '—') ?></code></td>
                            <td><?= isset($a['entity_id']) && $a['entity_id'] !== null ? (int) $a['entity_id'] : '—' ?></td>
                            <td class="small"><?= esc($a['description'] ?? '—') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
