# database_hotel
Creating simple hotel reservation system by utilizing MySQL database.

The making of this project has made us all gain experience on how to create and manipulate a database and implement a website that can store data into databases. 
The project helped members learn how to code in html, css, and php. 
We accomplished making a hotel directory website where the user is able to select a location, check in and out dates, and a party size, and then select a room, input their name, and successfully book a room. 
Users that are ‘staff’ have the ability to update and delete certain guest reservations when they log into the page using their staff ID. 



Step by Step instructions
 
1. Download hotel.zip
2. Move the hotel file into C:\xampp\htdocs
3. Create a database called Hotel
4. Import the Hotel.sql first (this is to create the tables)
5. Import the hotel_insert.sql (inserts the non-user input rows such as branches, floors, rooms etc)
6. Open your browser and type in localhost/hotel/index.php

For staff login (at the top right of the page),
Any staff_ID from the table “staff” will work as a login.  As an example, using 100 will ‘login’ to the staff page.  Anything else that is not a staff_ID will not work

guest_name is a primary key, please only put unique names.
In the staff.php page, setting the “Staying at all locations?” to “Yes” will not reveal any guests on purpose.  The purpose of including the option is to show our division query.
