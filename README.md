# Figma Design
- [User Pages](https://www.figma.com/design/WODTtcywtbgvfuzAwmd2iM/E-commerce-Website-Template-(Freebie)-(Community)?node-id=35-740&node-type=frame&t=SJUYJyWysxygRbV3-0)
- [Admin Pages](https://www.figma.com/design/Mh1T28FboLOfH7G6y18EQy/BankDash---Dashboard-UI-Kit---Admin-Template-Dashboard---Admin-Dashboard-(Community)?node-id=0-1&node-type=canvas&t=Rp7bfVyYpO4D3MAV-0)
- [Auth Pages](https://www.figma.com/design/T2ZDdG49DDs84t1zQhVH9e/Login-Page-Perfect-UI-(Freebie)-(Community)?node-id=8-2&node-type=canvas&t=H006bWqCuyjbcCTz-0)


# How to run this project


## Installation Guide Video
- Part 1: `https://youtu.be/ZSDCkz0vZ0U`
- Part 2: `https://youtu.be/MWiZQ4fh12k`
- Part 3: `https://youtu.be/_ht08Qp5QDs`


## Installation
- XAMPP version 8.2 
- Python version 3.x.x 
- Git latest version
- Composer latest version

## Set up 1
- Clone this project using git clone.
- Turn on MySQL in XAMPP first, it's better when you keep your password empty. (Username: root, Password: )
- Turn on Apache, then go to `phpMyAdmin` page.
- Create database name `webshop`.


## Import database
- Config file importSQL.py. In this file, you will see.
```
input_file = "webshop.sql"
xampp_mysql_path = "" 
db_user = "root"  
db_password = ""  
db_name = ""
```
`xampp_mysql_path` is mysql execution file path. In my case: `C:/xampp/mysql/bin`
`db_name` should be set `webshop`.
- Then execute it at root project directory (python importSQL.py) and check if they imported at `phpMyAdmin` page . In case the terminal said "python is not found", you need to set up in environment variable.

## Set up Apache
- Then turn off Apache.
- Open file httpd.conf in Apache config of XAMPP. Find something like `DocumentRoot "C:/xampp/htdocs" <Directory "C:/xampp/htdocs">`. You can easy find that by searching value `xampp/htdocs`. When you find it, you need to replace those two directory to root directory of project. Example in my case, I will replace those above with `C:\WebShopPHP`.
- Find this string `extension=gd` in `php.ini` and delete a semicolon before it to uncomment this line.
- Start Apache, and remember your HTTP port (not HTTPS). It can be 80 or 8080,... depends on your setting.


## Create .ENV
- First, we create `.env` file from `.env.example`.
- We have five constants at first. It's very straight forward. You don't need to change these if you keep default config in MySQL of XAMPP
   ```
     DB_NAME="webshop"
     DB_USER="root"
     DB_PASS=""
     DB_HOST="localhost"
     DB_DRIVER="pdo_mysql"
     ```

- Your URL. It's depends on your Apache HTTP Port.
```
WEB_HOST_URL="http://localhost:80"
  ```

- Google OAuth2, go to this link to create one `https://console.cloud.google.com/apis/credentials`
```
GOOGLE_CLIENT_ID=""
GOOGLE_CLIENT_SECRET=""
``` 

- Momo Test API. Just copy this.
```
MOMO_ENDPOINT="https://test-payment.momo.vn/v2/gateway/api/create"
MOMO_PARTNER_CODE="MOMO"
MOMO_ACCESS_KEY="F8BBA842ECF85"
MOMO_SECRET_KEY="K951B6PE1waDMi640xX08PD3vg6EkVlz"
```

- Email: The app need to login to your email so that send email to customer. 
```
EMAIL_HOST="smtp.gmail.com"
EMAIL_USERNAME=""
EMAIL_PASSWORD=""
EMAIL_PORT=587
EMAIL_NAME_DISPLAY="BK.CO"
```

- JWT: You can create a random string.
```
JWT_SECRET_KEY=""
```

## Download library and set up Autoload.
- Run at root project directory: `composer install`

## Cache route table
- You have to locate PHP executable file in Variable Environment.
- In root project root directory, type `php cli.php route:cache` to cache route table.






