# Works :rocket::rocket::rocket: 

## Initialization :dart:
- :white_check_mark: First Commit.

## Framework :dart:
- :white_check_mark: In creating self-framework process. Next Goal: Build module structure, get all controllers inside modules, register routes for those controllers imported.
- :white_check_mark: Add middleware and validator for framework.
- :white_check_mark: Add `cache` folder to save temp data.
- :white_check_mark: Add `.gitignore`, `composer.json`.
- :white_check_mark: Use autoloading.
- :white_check_mark: Use `namespace` instead of `require_once`. (Choose namespace)
- :white_check_mark: Simplify `view()` in `Controller` core class.
- :white_check_mark: Setting `route` by using `Attribute`. Example: `#[Get("/")]` or `#[Post("/product")]`.
- :white_check_mark: Setting `Middleware` by using `Attribute`. Example: `#[AuthMiddleware]`.
- :white_check_mark: Integrate `Database Migrations`, `ORM` of `Doctrine` library.
- :white_check_mark: Cache Routing Table. 
- :white_check_mark: Handle the route like `/:id/`. Get params from route. Auto pass params into action of controllers.
- :white_check_mark: Add `Dependency Injection`.
- :x: `Model` core. Auto parse data into `Model` when `Model` as a param of action. Example: `function index(string $id, PostModel $postModel)`.
- :white_check_mark: Add `LoadScripts`, `LoadStyleSheet` function for View Component.

- :x: Enhance performance by using `Redis` for caching instead of using cache file.
- :x: Sanatize request data.


## Function :dart: ??
### User

### Admin


## Dedicate to pages :dart:

### Stage one (HARD-CODED PAGE, MOBILE FIRST)
- :white_check_mark: Home Page.
- :white_check_mark: Product Detail Page.
- :white_check_mark: Shop Page.
- :white_check_mark: Cart Page.
- :white_check_mark: Login Page.
- :white_check_mark: Admin Page.

### Stage two (DESIGN DATABASE)
- :x: Plot ER Diagram.
- :x: Plot relational table.
- :x: Write Model and migrate into database (using ORM).
- :x: Create fake datas.


### Stage three (WRITE BACK-END)






## Short-term plan
- Design Database



# Functional
## Auth
- :white_check_mark: Regular register.
- :white_check_mark: Regular login
- :white_check_mark: Defend Route.