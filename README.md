## Laravel Example API APP

This is a simple laravel based REST API application consisting of following features:

-   User login and registration for 2 levels of users (admin and customers)
-   Customer CRUD APIs accessible to only admin users (using is_admin middleware)
-   Customers can login
-   Customers can view their profile info
-   Authentication is done using laravel sanctum package to generate API tokens which should be then send in the subsequest request authentication header
