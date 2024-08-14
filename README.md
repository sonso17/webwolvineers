# Welcome to the wolvineers web api
## Here i'm gonna upload some examples to thest this API:

## Users endpoints

### create new user and modify user(POST):
http://localhost/API/register

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
### Log In(POST):
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

http://localhost/API/xurzInr5Gb4bwDm.1.Admin/UserInfo/1

Allways apikey.user_id.Role

## Categories endpoints

### Create category i Modify Category
```
{
    "data": [
        {
            "CategoryName": "Ocells"
        }
    ]
}
```



### get category
http://localhost/API/j1MUXD1E3Byx3Ml.1.Admin/GetOneCategories/1

### get all categories
http://localhost/API/j1MUXD1E3Byx3Ml.1.Admin/GetAllCategories

### Delete category
```
{
    "data": [
        {
            "Category_id": 1
        }
    ]
}
```

## paetrons endpoints
### Create paetron and modify paetron
```
{
    "data": [
        {
            "paetron_name": "samsung",
            "paetron_logo": "samsung.jpg",
            "paetron_link": "https://samsung.es"
        }
    ]
}
```

## Article endpoints
### Create article

http://localhost/API/xurzInr5Gb4bwDm.1.Admin/CreateArticle

```
{
    "data": 
        {
            "visibility": "true",
            "article_title": "Article prova wolvineers",
            "descripcio": "descripcio article prova",
            "article_status": "revisio",
            "user_id": "1",
            "category_id": "1",
            "props": [
                {
                    "prop_id": 0,
                    "prop_val": "wolvineers primer paragref",
                    "position": 1
                },
                {
                    "prop_id": 1,
                    "prop_val": "imatge.jpg",
                    "position": 2
                },
                {
                    "prop_id": 0,
                    "prop_val": "wolvineers segon paragref",
                    "position": 3
                },
                {
                    "prop_id": 2,
                    "prop_val": "http://linkwolvineers.cat/",
                    "position": 4
                },
                {
                    "prop_id": 1,
                    "prop_val": "imatge2.jpg",
                    "position": 5
                },
                {
                    "prop_id": 0,
                    "prop_val": "wolvineers tercer paragref",
                    "position": 6
                },
                {
                    "prop_id": 1,
                    "prop_val": "imatge3.jpg",
                    "position": 7
                }
            ]
        }
    
}
```

http://localhost/API/hXa48Fx7Yut14j1.1.Admin/CreateArticle