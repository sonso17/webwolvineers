# Welcome to the wolvineers web api
## Here i'm gonna upload some examples to thest this API:

### create new user and modify user:
http://localhost/API/xurzInr5Gb4bwDm.1/ModifyUser/1

```
{
    "data": [
        {
            "UserName": "Frick",
            "UserEmail": "fedrick@fedrick.cat",
            "UserRole": "Admin",
            "pass": "1234",
            "ProfilePic": "hola.jpg"
        }
    ]
}
```
### Log In:
```
{
    "data": [
        {
            "UserName": "Frick",
            "pass": "1234"
        }
    ]
}
```
 

### Get user info and GetAllUsers

http://localhost/API/xurzInr5Gb4bwDm.1/UserInfo/1

Allways apikey.user_id

