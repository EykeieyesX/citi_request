 <?php include './url.php'; ?>

 <script>
     var user_type = localStorage.getItem('user_type');
 </script>

 <?php include './assets/js/loginchecker.php'; ?>
 <style>
     .text-center {
         width: 200;
         /* Ensures it takes full width */
     }

     .logo {
         margin-left: auto;
         /* Centers the logo horizontally */
         margin-right: auto;
         /* Centers the logo horizontally */
     }
 </style>

 <head>
     <link href="./assets/img/lgu.png" rel="icon">
     <link href="./assets/img/lgu.png" rel="lgu.png">

 </head>
 <header id="header" class="header fixed-top d-flex align-items-center">

     <i class="bi bi-list toggle-sidebar-btn"></i>

     </a>

     </div>
     <nav class="header-nav ms-auto">
         <ul class="d-flex align-items-center">



             <!-- Notification Dropdown -->
             <li class="nav-item dropdown">
                 <a id="btn_notifications" class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                     <i class="bi bi-bell"></i>
                     <span class="badge bg-primary badge-number"></span>
                 </a><!-- End Notification Icon -->

                 <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                     <li class="dropdown-header">
                         You have no new notifications
                         <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                     </li>
                     <li>
                         <hr class="dropdown-divider">
                     </li>
                     <li class="dropdown-footer">
                         <a href="#">Show all notifications</a>
                     </li>
                 </ul><!-- End Notification Dropdown Items -->
             </li><!-- End Notification Nav -->

             <!-- Message Dropdown -->
             <li class="nav-item dropdown">
                 <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Messages">
                     <i class="bi bi-chat-left-text"></i>
                     <span class="badge bg-success badge-number"></span>
                 </a><!-- End Messages Icon -->

                 <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                     <li class="dropdown-header">
                         You have no new messages
                         <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                     </li>
                     <li>
                         <hr class="dropdown-divider">
                     </li>
                     <li class="dropdown-footer">
                         <a href="#">Show all messages</a>
                     </li>
                 </ul><!-- End Messages Dropdown Items -->
             </li><!-- End Messages Nav -->

             <li class="nav-item dropdown pe-3">
                 <a id="navbar_fullname" class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Profile">
                     <img src="./assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                     <span class="d-none d-md-block dropdown-toggle ps-2">--</span>
                 </a><!-- End Profile Image Icon -->

                 <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                     <li class="dropdown-header">
                         <h6 id="nav_user_name">--</h6>
                         <span id="nav_user_type">--</span>
                     </li>
                     <li>
                         <hr class="dropdown-divider">
                     </li>

                     <li>
                         <a id="profile_link" class="dropdown-item d-flex align-items-center" href="users-profile.php">
                             <i class="bi bi-person"></i>
                             <span>My Profile</span>
                         </a>
                     </li>
                     <li>
                         <hr class="dropdown-divider">
                     </li>

                     <li>
                         <a class="dropdown-item d-flex align-items-center" href="users-profile.php">
                             <i class="bi bi-gear"></i>
                             <span>Account Settings</span>
                         </a>
                     </li>
                     <li>
                         <hr class="dropdown-divider">
                     </li>

                     <li>
                         <a class="dropdown-item d-flex align-items-center" href="pages-faq.php">
                             <i class="bi bi-question-circle"></i>
                             <span>Need Help?</span>
                         </a>
                     </li>
                     <li>
                         <hr class="dropdown-divider">
                     </li>

                     <li id="btn_logout" onclick="Logout()">
                         <a class="dropdown-item d-flex align-items-center">
                             <i class="bi bi-box-arrow-right"></i>
                             <span>Sign Out</span>
                         </a>
                     </li>

                     <form id="logout-form" method="post" style="display: none;">
                         <input type="hidden" name="logout" value="true">
                     </form>
             </li>

         </ul>
         </li>

         </ul>
     </nav>

 </header>





 <!-- Template Main CSS File -->
 <link href="./assets/css/style.css" rel="stylesheet">

 <script src="./assets/js/jquery/jquery.min.js"></script>




 <script>
     const result = LoginChecker();

     var firstname = result.data.data.user_fname
     var lastname = result.data.data.user_lname
     var email = result.email
     var user_type = result.data.data.user_type
     var user_name = result.data.data.user_name


     $("#navbar_fullname").html(firstname + " " + lastname)
     $('#profile_link').attr('href', 'profile.php?link=' + email);
     $('#nav_user_name').html(user_name);
     $('#nav_user_type').html(user_type);

     localStorage.setItem('user_type', user_type);


     const currentPage = window.location.pathname.split('/').pop();

     if (currentPage == "admin-index.php") {

         $("#btn_notifications").removeClass('d-none');


         if (user_type.toUpperCase() == "USER") {
             window.location.href = 'index.php';
         } else if (user_type.toUpperCase() == "SOCIAL WORKER") {
             window.location.href = 'social_workers-index.php';
         }
     } else if (currentPage == "index.php") {

         $("#btn_notifications").addClass('');
         $("#nav_user_type").addClass('d-none');



         if (user_type.toUpperCase() == "ADMIN") {
             window.location.href = 'admin-index.php';
         } else if (user_type.toUpperCase() == "SOCIAL WORKER") {
             window.location.href = 'social_workers-index.php';
         }
     } else if (currentPage == "social_workers-index.php") {

         $("#btn_notifications").removeClass('d-none');


         if (user_type.toUpperCase() == "ADMIN") {
             window.location.href = 'admin-index.php';
         } else if (user_type.toUpperCase() == "USER") {
             window.location.href = 'index.php';
         }
     }

     function Logout() {

         localStorage.clear()
         window.location.href = 'login.php'

     }
 </script>