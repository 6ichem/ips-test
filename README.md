<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## Project Documentation
### Getting Started
Welcome to my Laravel project! Before you can run the application, there are a few initial steps you need to follow to set up the database with some essential data. I improvised a little to implement a fully functional demo that includes all of the requirements and some more features (as requested <a href="https://ipsmedia.notion.site/ipsmedia/Back-end-Developer-Test-26cb7ae808204668a6ca3c408eaa6d4f">here</a>)

### Database Seeding
Before you can run the application, you need to seed the database with some initial data. Run the following commands to populate the database:
- Seed the default data:
```bash
php artisan db:seed
```
- Seed the Achievement data:
```bash
php artisan db:seed --class=AchievementSeeder
```
- Seed the Badge data:

```bash
php artisan db:seed --class=BadgeSeeder
```

### Running the Application
```bash
php artisan serve
```
The application will be available at http://localhost:8000 by default. You can access it in your web browser.


## API Routes
### Get User Achievements

- **Route**: `/users/{user}/achievements`
- **Controller**: `AchievementsController::class`
- **Method**: `index`
- **Description**: This route is used to retrieve the achievements of a specific user.

### Custom Authentication Endpoints

- **Prefix**: `/auth`
  
  - **Route**: `/auth/register`
  - **Controller**: `UserController::class`
  - **Method**: `register`
  - **Description**: This route allows a user to register a new account.

  - **Route**: `/auth/login`
  - **Controller**: `UserController::class`
  - **Method**: `login`
  - **Description**: This route allows a user to log in to their existing account.

### Create Comment (Authenticated)

- **Route**: `/create-comment`
- **Controller**: `CommentController::class`
- **Method**: `store`
- **Middleware**: `auth`
- **Description**: This route is used to create a new comment. Authentication is required to access this route.

### Watch Lesson (Authenticated)

- **Route**: `/watch-lesson/{lesson}`
- **Controller**: `LessonController::class`
- **Method**: `watch`
- **Middleware**: `auth`
- **Description**: This route is used to mark a lesson as watched by a user. Authentication is required to access this route.

## Controllers Documentation
### AchievementsController
- The AchievementsController is responsible for retrieving a user's achievements and related data. It fetches information such as the user's unlocked achievements, the next available achievements to unlock, the user's current badge (if any), the name of the next badge, and calculates the remaining achievements needed to unlock the next badge.

### CommentController
- The CommentController handles the creation of comments. Users can submit comments with a specified body text, and the controller ensures that the comment is associated with the currently authenticated user. It also triggers an event (CommentWritten) when a comment is successfully created.

### LessonController
- The LessonController allows users to mark a lesson as watched. If a lesson is not already marked as watched by the user, it attaches the lesson to the user's watched lessons list. Additionally, it dispatches an event (LessonWatched) to capture information about the lesson being watched by the user.

### UserController
- The UserController primarily deals with user authentication and profile management. It provides the following functionality:
    - ``get``: Retrieves the authenticated user's profile.
    - ``register``: Allows new users to register by providing their name, email, and password. It assigns a default badge to the user during registration.
    - ``login``: Handles user login by validating email and password and returning an authentication token if successful.

## Events and Listeners Documentation
### HandleAchievementUnlocked
- Event: AchievementUnlocked
- Description: This event listener responds when a user unlocks an achievement. It performs the following actions:
    - Retrieves information about the user and the type of achievement unlocked (e.g., "Lesson" or "Comment").
    - Calculates the total count of relevant achievements for the user.
    - Fetches type-related achievements from the database based on the required count and user progress.
    - Attaches the earned achievements to the user.
    - Logs the unlocked achievements.
    - Dispatches the BadgeUnlocked event to potentially unlock a badge for the user.
### HandleBadgeUnlocked
- Event: BadgeUnlocked
- Description: This event listener handles the event when a user unlocks a badge. It performs the following tasks:
    - Retrieves information about the user and their achievements.
    - Searches for an eligible badge based on the number of achievements earned.
    - If an eligible badge is found, it detaches any existing badge (if present) and attaches the newly earned badge to the user.
    - Logs the unlocked badge.
### HandleCommentWritten
- Event: CommentWritten
- Description: This event listener responds to the event when a new comment is written by a user. It performs the following action:
    - Retrieves the comment and the associated user.
    - Dispatches the AchievementUnlocked event with the user and the type "Comment" to potentially unlock relevant achievements.
### HandleLessonWatched
- Event: LessonWatched
- Description: This event listener handles the event when a user marks a lesson as watched. It performs the following action:
    - Retrieves information about the watched lesson and the user who watched it.
    - Dispatches the AchievementUnlocked event with the user and the type "Lesson" to potentially unlock relevant achievements.

## Additional Information
For more details on using and customizing Laravel, please refer to the Laravel documentation. If you encounter any issues or have questions, feel free to reach out for assistance.

Happy coding! 🚀
