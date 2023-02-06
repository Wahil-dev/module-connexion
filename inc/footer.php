<footer>
    <div class="container flex-r">
        <p>&copy; All rights reserved </p>
        <?php if(isConnected()) :?>
            <ul class="flex-r">
                <li><a href="#"><i class="fa-brands fa-github"></i><?php echo htmlspecialchars($_SESSION['login']->login) ?>-github</a></li>
                <li><a href="#"><i class="fa-brands fa-linkedin"></i><?php echo htmlspecialchars($_SESSION['login']->login) ?>-linkedin</a></li>
            </ul>
        <!-- Pour les visiteurs -->
        <?php else :?>
            <ul class="flex-r">
                <li><a href="#"><i class="fa-brands fa-github"></i>compte-github</a></li>
                <li><a href="#"><i class="fa-brands fa-linkedin"></i>compte-linkedin</a></li>
            </ul>
        <?php endif; ?>
    </div>
</footer>
