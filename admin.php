<?php
if (!isset($_SESSION['user']['valid']) || $_SESSION['user']['valid'] !== 'true') {
    $_SESSION['message'] = '<p>Please register or login using your credentials!</p>';
    header("Location: index.php?menu=6");
    exit;
}

$action = isset($_GET['action']) ? (int)$_GET['action'] : 1;

$usersClass = ($action === 1) ? 'admin-link active' : 'admin-link';
$newsClass = ($action === 2) ? 'admin-link active' : 'admin-link';

$hnbParentClass = in_array($action, [3, 4], true)
    ? 'admin-link active admin-link-parent'
    : 'admin-link admin-link-parent';

$xmlClass = ($action === 3) ? 'admin-sublink active' : 'admin-sublink';
$jsonClass = ($action === 4) ? 'admin-sublink active' : 'admin-sublink';

print '
<section class="admin-panel">
    <div class="admin-header">
        <span class="admin-label">Admin panel</span>
        <h1>Administration</h1>
        <p>Manage users, news and API tools in one place.</p>
    </div>

    <div class="admin-section">
        <div class="admin-nav-wrap">
            <ul class="admin-nav">
                <li>
                    <a class="'.$usersClass.'" href="index.php?menu=7&amp;action=1">
                        <span class="admin-link-title">Users</span>
                        <span class="admin-link-text">User management and overview</span>
                    </a>
                </li>
                <li>
                    <a class="'.$newsClass.'" href="index.php?menu=7&amp;action=2">
                        <span class="admin-link-title">News</span>
                        <span class="admin-link-text">Create, edit and manage news</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    

    <div class="admin-content">';
    
    switch ($action) {
        case 1:
            include("admin/users.php");
            break;

        case 2:
            include("admin/news.php");
            break;

        case 3:
            include("admin/hnb-xml.php");
            break;

        case 4:
            include("admin/hnb-json.php");
            break;

        default:
            include("admin/users.php");
            break;
    }

print '
    </div>
</section>';
?>