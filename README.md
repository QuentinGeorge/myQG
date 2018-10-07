# myQG
personal section of my website

## Modifications to made for deployment on online server
- Modify /configs/db.ini with online server db informations
- Modify /configs/setting.php => define( 'PROJECT_PATH', 'http://www.quentin-george.com/myQG/' );
- Modify /models/User.php => $sSql = 'SELECT * FROM myqg_users WHERE name = :user AND password = :password'; (replace myqg_users by the db table name)
