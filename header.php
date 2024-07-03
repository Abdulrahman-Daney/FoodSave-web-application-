

<header>
    <nav>
        <div class="logo">
            <span class="logo-text">
                <span class="food">Food</span> <span class="save">Save</span>
            </span>
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            
            <?php if (isset($_SESSION['username'])) { ?>
            
            <li><a href="logout.php">Log out</a></li> 
            <li><a href="howwework.php">How we work</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li>
                <button class="contact-us" onclick="contactUs()">Contact Us</button>
            </li>
            <li style="display: flex; flex-direction: column; align-items: center;">
                <img style="width: 40px;" src="./usericon.png" alt="User Icon">
                <span><?php echo $_SESSION['username']; ?></span>
            </li>
            
            <?php } else { ?>
            
            <li><a href="login.php">Log in</a></li>
            <li><a href="signup.php">Sign up</a></li>
            <li><a href="howwework.php">How we work</a></li>
            <li>
                <button class="contact-us" onclick="contactUs()">Contact Us</button>
            </li>
            
            <?php } ?>
        </ul>
    </nav>
</header>
