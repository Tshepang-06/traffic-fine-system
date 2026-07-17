# Traffic Fine System

A web-based traffic fine management system built for metro traffic officers.
The system enables officers to issue fines, track officer activity and detects suspicious activity such as bribe-taking automatically.

---

## Features

- Officer registration and login system
- Issue traffic tickets to drivers
- Dashboard showing total tickets per day, paid fines, unpaid Fines, officer location
- Each ticket is linked to the officer's national ID automatically
- Bribe detection timer that monitors officer's activity
- Head office to show flagged officers

---

## How the Bribe Detection Works

- Timer starts automatically when an officer logs in

- when **2 minutes** pass without any ticket given, the first warning pops up on the screen
- when **3 minutes** pass without any ticket given, the second warning pops up on the screen
- when **4 minutes** pass without any ticket given, the officer is reported to head office

Timer resets and starts again only when officer issues a ticket.

## The Logout Detection Logic

This is the most important feature of the system. It is applied when officer receives the 1st warning
and decides to log out and log back in to reset the timer:

- The 1st warning is **already sent** to the Head Office through the suspicious activity monitoring feature
- The 2nd warning will still **be sent** even when officer logs out and logs back in
- If the Head Office shows **more than 3 warnings without a bribe report**
  it means the officer is logging in and out continually to avoid being reported
- This pattern provides strong evidence that the officer is taking bribes
- Head office can then conduct further inspections based on this pattern

---

## Technologies Used

- **PHP** - backend logic and database connection
- **MySQL** - database for officers, tickets and alerts
- **JavaScript** - inactivity timer and popup alerts
- **HTML & CSS** - frontend pages
- **XAMPP** - local development server

---

## Database Tables

- 'Officers' - stores officer registration data
- 'tickets' - stores all the traffic tickets
- 'location_analysis' - tracks tickets pace per location
- 'law_breakers' - stores warnings and bribe reports

---

## Installation

1. Install XAMPP on your local machine
2. Clone this repository into 'C:\xampp\htdocs\'
3. Open phpMyAdmin and create a database called 'officer_db'
4. Import the SQL schema file to create all tables
5. Open your browser and search for 'localhost/traffic-fine-system/login_site.html'

---

## Pages

- 'registration.html' - registration of new officer
- 'login_site.html' - login page of officer
- 'dash.php' - officer dashboard
- 'Tickets.php' - issue a new ticket
- 'HeadOffice.php' - flagged officers page

---

## Author

Built by Tshepang Motete - aspiring backend developer passionate about building systems that solve real world problems
