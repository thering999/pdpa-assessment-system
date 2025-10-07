<?php
// Show reviewer timeline for document review steps
// $steps expected from parent

if (empty($steps)) return;

echo '<div style="margin:10px 0;">';
echo '<div style="font-size:13px;color:#666;margin-bottom:8px;">📋 ไทม์ไลน์การรีวิว</div>';
echo '<div style="max-height:200px;overflow-y:auto;border:1px solid #e0e0e0;border-radius:6px;">';

foreach ($steps as $idx => $s) {
    $actionText = $s['action'];
    switch($s['action']) {
        case 'PENDING': $actionText = '⏳ รอตรวจสอบ'; $color = '#999'; break;
        case 'PASS': $actionText = '✅ อนุมัติ'; $color = '#4caf50'; break;
        case 'FAIL': $actionText = '❌ ไม่อนุมัติ'; $color = '#f44336'; break;
        case 'COMMENT': $actionText = '💬 ให้ความเห็น'; $color = '#ff9800'; break;
        default: $color = '#666';
    }
    
    echo '<div style="padding:8px 12px;border-bottom:1px solid #f0f0f0;' . ($idx === count($steps) - 1 ? 'border-bottom:none;' : '') . '">';
    echo '<div style="display:flex;justify-content:space-between;align-items:center;">';
    echo '<strong style="color:' . $color . ';">' . htmlspecialchars($actionText) . '</strong>';
    echo '<small style="color:#999;">' . htmlspecialchars($s['created_at']) . '</small>';
    echo '</div>';
    
    if (!empty($s['reviewer_id'])) {
        try {
            $reviewer = db()->prepare('SELECT username FROM users WHERE id = ?');
            $reviewer->execute([$s['reviewer_id']]);
            $reviewerName = $reviewer->fetchColumn();
            if ($reviewerName) {
                echo '<div style="font-size:12px;color:#666;margin-top:2px;">โดย: ' . htmlspecialchars($reviewerName) . '</div>';
            }
        } catch (Throwable $e) { /* ignore */ }
    }
    
    if (!empty($s['notes'])) {
        echo '<div style="margin-top:4px;padding:4px 8px;background:#f8f9fa;border-radius:4px;font-size:13px;">';
        echo nl2br(htmlspecialchars($s['notes']));
        echo '</div>';
    }
    echo '</div>';
}

echo '</div>';
echo '</div>';
?>