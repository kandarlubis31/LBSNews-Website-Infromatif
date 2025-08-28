<div class="sidebar">
    <div class="logo">
        <a href="index.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            Admin Panel
        </a>
    </div>

    <nav class="admin-nav">
        <ul>
            <li>
                <a href="index.php" class="nav-link <?php echo ($current_admin_page == 'dashboard') ? 'active' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="artikel.php" class="nav-link <?php echo ($current_admin_page == 'artikel') ? 'active' : ''; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    Artikel
                </a>
            </li>
            <li>
                <a href="kategori.php" class="nav-link <?php echo ($current_admin_page == 'kategori') ? 'active' : ''; ?>">
                     <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                    Kategori
                </a>
            </li>
        </ul>
    </nav>

    <div class="admin-footer">
        <p>Login sebagai <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
        <a href="../index.php" target="_blank" class="button-primary button-small" style="width:100%; text-align:center; margin-bottom: 10px;">Lihat Situs</a>
        <a href="logout.php" class="button-danger button-small" style="width:100%; text-align:center;">Logout</a>
    </div>
</div>