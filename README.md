# FanSquadServe

## Introduction

**FanSquadServe (FSS)** is a ticket management system that records tutoring service request tickets created by Fanshawe students, allows the LabSquad tutors and faculties/staffs to manage ticket details, visualizes ticket data, and manage users.

## App Demo
https://fss-app-amber.vercel.app/

## Installation

- Install Laravel & Composer at https://laravel.com/docs/10.x/installation
- Install Node.js & npm at https://nodejs.org/en/download
- Install MySQL server & a client of your choice
- Clone the git repo to your local machine from https://github.com/fansquadserve/fansquadserve
- Clone **`.env.example`** file content to a new file in the same directory and name it **`.env`**. In this file:
  - Run `php artisan key:generate` and replace `APP_KEY` variable with the generated key
  - Update the following environment variables for your DB connection
    - DB_CONNECTION=mysql
    - DB_HOST=127.0.0.1
    - DB_PORT=3306
    - DB_DATABASE=laravel
    - DB_USERNAME=root
    - DB_PASSWORD=
- Initialize DB by running Laravel migration and seeder with command `php artisan migrate:fresh --seeder DatabaseSeeder`
  - Note: never use this command on Prod environment
- Run BE server with command `php artisan serve`
- Run FE server with command `npm run dev`

## Deployment
**Example Vercel Deployment**
- Fork the git repo from https://github.com/fansquadserve/fansquadserve
- Setup your Vercel account at https://vercel.com 
- Create a project connecting to the git repo
- In Settings/Environment Variables tab
  - Update the following variables to the same value as in your **`.env`** file
    - APP_KEY
    - DB_CONNECTION
    - DB_HOST
    - DB_PORT
    - DB_DATABASE
    - DB_USERNAME
    - DB_PASSWORD
  - Add the following environment variables to enable authentication features for the Vercel app domain
    - SESSION_DOMAIN=`{your_domain}`
    - SANCTUM_STATEFUL_DOMAINS=`{your_domain}`
    - e.g. SESSION_DOMAIN=fansquadserve.vercel.app
- Deploy automatically by pushing new commits to `main` branch or redeploy in Deployments tab

## Tech Stack

### Backend

- Laravel (PHP): https://laravel.com/
- MySQL
- REST API

### Frontend

- Vue.js: https://vuejs.org/
- Pinia: https://pinia.vuejs.org/
- TailwindCSS: https://tailwindui.com/
- PrimeVue: https://primevue.org/
- VueForm: https://vueform.com/

## App Specifications

### Landing Page

- Route name: `/landing`
- Link to Service Request Page (Student)
- Link to Login Page (LabSquad tutors, faculties or staffs)

### Service Request Page

- Route name: `/service-request`
- Include form for Student to submit their service request for tutoring
- After selecting a course and a tutor, Student is redirected to that tutor's scheduling page (such as MS Bookings) to book their appointment using the Reference Number generated by FSS
- Student should come back to FSS to finish creating their service request
- Show a confirmation message including the information that the Student submitted in the form

### Login Page

- Route name: `/login`
- Where the FSS users (LabSquad tutors, faculties or staffs) can log in
- Note: in the scope of this app, FSS users only refer to the LabSquad tutors, faculties or staffs, and not the Students

### Ticket Queue Page (Home page)

- Route name: `/tickets`
- Default page that the FSS users will arrive at after logging in
- Include `List of Tickets` table where each ticket has a link to their detail page
- Table offers sort and filter capabilities in some fields, such as Ticket ID or Tutor

### Ticket Details Page

- Route name: `/ticket-details`
- Include details of the specified ticket
- LabSquad tutors can update fields such as
    - Scheduled Start Time
    - Scheduled End Time
    - Priority
    - Status
    - Assignee
    - Tutor Note
- Actual Start Time & Actual End Time will be updated when ticket status is moved to "In-progress" and "Resolved", respectively

### Data Dashboard Page

- Route: `/data-dashboard`
- Include 3 charts
    1. Tickets by Date: bar chart that displays ticket data grouped by each hour/date/month, depends on the selected date filter (e.g. Last 7 days filter will show number of tickets created on each day)
    2. Tickets by Status: bar chart that displays ticket data based grouped by each status
        - New
        - Confirmed
        - In-progress
        - Resolved
        - Escalated
        - On-hold
        - Closed
    3. Average SLA By Priority: bar chart that displays average `Time to First Response (TFR)` and `Time to Resolution (TR)` grouped by ticket priority (Low, Medium or High)
- Offer Date filter with the following options
    - Last 24 hours
    - Last 7 days
    - Last 30 days
    - Last year
- Offer Assignee filter where all or specific assignee(s) can be selected

### User Management Page

- Route: `/users`
- Only accessible to users with role "Admin"
- Include `List of Users` table where each user has a link to their detail page
- Table offers sort and filter capabilities in some fields, such as User ID, First Name or Last Name

### User Details Page

- Route: `/users-details`
- Admin can update fields such as Role, Course, or Is Active

### User Details Page

- Route: `/add-user`
- Admin can add new user and assign role 

### User Profile Page
- Route: `/user-profile`
- Current logged in user can view their information and assigned courses (for LabSquad tutors)

## Screenshots
- Landing Page
![LandingPage](https://github.com/minhanhbui97/fansquadserve/assets/49821510/9f5d5453-a202-48c0-81b9-f344e29b6497)
- Service Request Page
![ServiceRequestPage](https://github.com/minhanhbui97/fansquadserve/assets/49821510/14ec16ea-7e74-470a-986f-bad591966425)
- Ticket Queue Page
![TicketQueuePage](https://github.com/minhanhbui97/fansquadserve/assets/49821510/6cb39ec2-a190-4278-864a-d72019458e3e)
- Data Dashboard Page
![DataDashboardPage](https://github.com/minhanhbui97/fansquadserve/assets/49821510/c85d109e-3b4a-4b66-ac90-9ca71ba06ce7)
