<?php
// Session & Security Check - Keeps the page locked
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.html");
    exit();
}

// 1. INCLUDE THE DATABASE CONNECTION
require_once 'db.php';

// Get User Info for the Navbar UI
$userName = $_SESSION['user_name'] ?? 'User';
$userInitial = strtoupper(substr($userName, 0, 1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Hub Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Modern CSS Variables */
        :root {
            --bg-color: #f8fafc;
            --surface-color: #ffffff;
            --primary-text: #0f172a;
            --secondary-text: #64748b;
            --accent-color: #2563eb;
            --accent-hover: #1d4ed8;
            --border-color: #e2e8f0;
            --radius-md: 8px;
            --radius-lg: 16px;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--primary-text);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* Top Navigation Bar */
        .navbar {
            background-color: var(--surface-color);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--accent-color);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--secondary-text);
            border: 1px solid var(--border-color);
        }

        .btn-outline:hover {
            background-color: #f1f5f9;
            color: var(--primary-text);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--secondary-text);
            border-left: 1px solid var(--border-color);
            padding-left: 1.5rem;
        }

        .avatar {
            background: linear-gradient(135deg, var(--accent-color), #8b5cf6);
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }

        /* Main Dashboard Layout */
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        /* Enhanced Search Header */
        .search-header {
            text-align: center;
            margin-bottom: 4rem;
            padding: 3rem 2rem;
            background: linear-gradient(to bottom, #ffffff, var(--bg-color));
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
        }

        .search-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
            color: #0f172a;
        }

        .search-header p {
            color: var(--secondary-text);
            font-size: 1.1rem;
            margin-bottom: 2.5rem;
        }

        .search-wrapper {
            position: relative;
            max-width: 640px;
            margin: 0 auto;
        }

        .search-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            width: 20px;
            height: 20px;
        }

        .search-input {
            width: 100%;
            padding: 1.25rem 1.25rem 1.25rem 3.5rem;
            font-size: 1.05rem;
            font-family: inherit;
            border: 1px solid var(--border-color);
            border-radius: 50px;
            background-color: var(--surface-color);
            transition: var(--transition);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .content-card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }

        .content-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
            border-color: var(--accent-color);
        }

        .badge {
            align-self: flex-start;
            background-color: #f1f5f9;
            color: var(--secondary-text);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--primary-text);
        }

        .card-snippet {
            color: var(--secondary-text);
            font-size: 0.95rem;
            line-height: 1.6;
            flex-grow: 1;
        }

        /* Empty State */
        .no-results {
            display: none;
            text-align: center;
            padding: 3rem;
            color: var(--secondary-text);
            grid-column: 1 / -1;
        }

        @media (max-width: 768px) {
            .search-header h1 { font-size: 2rem; }
            .content-grid { grid-template-columns: 1fr; }
            .nav-actions .btn-outline { display: none; }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
            Knowledge Hub
        </a>
        
        <div class="nav-actions">
            <a href="upload-content.html" class="btn btn-primary">+ Create Post</a>
            <a href="logout.php" class="btn btn-outline">Log Out</a>
            
            <div class="user-profile">
                <span><?php echo htmlspecialchars($userName); ?></span>
                <div class="avatar"><?php echo htmlspecialchars($userInitial); ?></div>
            </div>
        </div>
    </nav>

    <main class="dashboard-container">
        
        <header class="search-header">
            <h1>How can we help you today?</h1>
            <p>Search across documentation, guidelines, and articles.</p>
            
            <div class="search-wrapper">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="searchInput" class="search-input" placeholder="Search by title, keyword, or category..." autocomplete="off">
            </div>
        </header>

        <section class="content-grid" id="contentGrid">
            
            <?php
            // 2. FETCH POSTS FROM THE DATABASE
            // This grabs the newest posts first
            $sql = "SELECT * FROM posts ORDER BY post_id DESC";
            $result = mysqli_query($conn, $sql);

            // 3. LOOP THROUGH THE RESULTS AND DISPLAY THEM
            if ($result && mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    
                    // Limit the content text so it doesn't break the card design
                    $snippet = substr($row['content'], 0, 150);
                    if (strlen($row['content']) > 150) {
                        $snippet .= '...';
                    }
                    ?>
                    
                    <article class="content-card">
                        <span class="badge"><?php echo htmlspecialchars($row['genre']); ?></span>
                        <h2 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h2>
                        <p class="card-snippet"><?php echo htmlspecialchars($snippet); ?></p>
                    </article>

                    <?php
                }
            }
            ?>

            <article class="content-card">
                <span class="badge">Engineering</span>
                <h2 class="card-title">API Integration Guide</h2>
                <p class="card-snippet">Learn how to authenticate and connect to our core REST APIs using OAuth 2.0. Includes code examples for Node and Python.</p>
            </article>

            <article class="content-card">
                <span class="badge">Design</span>
                <h2 class="card-title">Brand Identity Assets</h2>
                <p class="card-snippet">Download official logos, color palettes, and typography guidelines for creating marketing materials and presentations.</p>
            </article>

            <article class="content-card">
                <span class="badge">HR & Admin</span>
                <h2 class="card-title">Employee Onboarding Checklist</h2>
                <p class="card-snippet">Step-by-step instructions for setting up new team members, including software access, hardware requests, and compliance forms.</p>
            </article>

            <article class="content-card">
                <span class="badge">Product</span>
                <h2 class="card-title">Q3 Feature Roadmap</h2>
                <p class="card-snippet">A detailed overview of upcoming product features, expected release dates, and beta testing opportunities for the next quarter.</p>
            </article>

            <article class="content-card">
                <span class="badge">Security</span>
                <h2 class="card-title">Data Privacy Policies</h2>
                <p class="card-snippet">Guidelines on handling customer data, GDPR compliance requirements, and internal data security protocols.</p>
            </article>

            <article class="content-card">
                <span class="badge">Support</span>
                <h2 class="card-title">Troubleshooting Login Issues</h2>
                <p class="card-snippet">Common solutions for password resets, multi-factor authentication (MFA) lockouts, and SSO configuration errors.</p>
            </article>

            <div class="no-results" id="noResults">
                <h3>No articles found</h3>
                <p>Try adjusting your search terms or checking your spelling.</p>
            </div>

        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const cards = document.querySelectorAll('.content-card');
            const noResults = document.getElementById('noResults');

            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                let hasVisibleCards = false;

                cards.forEach(card => {
                    const cardText = card.textContent.toLowerCase();
                    if (cardText.includes(searchTerm)) {
                        card.style.display = 'flex';
                        hasVisibleCards = true;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (hasVisibleCards) {
                    noResults.style.display = 'none';
                } else {
                    noResults.style.display = 'block';
                }
            });
        });
    </script>
</body>
</html>