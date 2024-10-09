<nav>
    <ul>
        <li><a href="/dashboard" <?php echo ($_SERVER['REQUEST_URI'] === '/dashboard') ? 'class="active"' : ''; ?>>Dashboard</a></li>
        <!--<li><a href="/course" <?php echo ($_SERVER['REQUEST_URI'] === '/course') ? 'class="active"' : ''; ?>>Courses</a></li>-->
        <li><a href="/logout" <?php echo ($_SERVER['REQUEST_URI'] === '/logout') ? 'class="active"' : ''; ?>>Logout</a></li>
    </ul>
    <style>
        nav {
            background-color: #333;
            padding: 10px;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 10px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        nav ul li a.active {
            font-weight: bold;
        }
    </style>
</nav>
