# Welcome to the wolvineers web api

## Hou to set up the API on a simple XAMPP:
1- Install Xampp and download the API from the API branch. Also you may need Postman program for better testing.

2- once Xampp installed, get inside the ```htdocs``` directory(C:\xampp\htdocs) and create the ```api``` directory inside.

3- Copy all the files downloaded from my APi branch and paste them inside the ```api```  directory.

4- run xampp control panel and activate the ```apache2``` and the ```MySQL``` services.

5- To create the database, you have to open your browser and go to the addres ```http://localhost/phpmyadmin```, it my have a log in form. the default credentials are: leave the user box empty and the password box put ```root```. Once inside, copy all the code from ```scriptBDD.sql```

## How does my API works?
This API, by now has the following structure:

#### The domain name:
```http://localhost/API```

#### $recurs1:
```http://localhost/API/recurs1```

This space, is used for the public endpoints for example ```login```, ```SelectPublicArticles```, etc... Also is used for getting acces to the user and admin endpoints, but for that, you need to use that space for the user identificator following that:

```APIKEY.user_id.role```

A complete example could be like:

```http://localhost/API/APIKEY.user_id.role/CreateArticle```

## Here i'm gonna upload some examples to thest this API:

## Users endpoints

### create new user and modify user(POST):
```http://localhost/API/register```

```http://localhost/API/xurzInr5Gb4bwDm.1/ModifyUser/1```

```
{
    "data": [
        {
            "UserName": "wolvineers13",
            "UserEmail": "wolvi@wolvineers.cat",
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
            "UserEmail": "wolvi@wolvineers.cat",
            "pass": "1234"
        }
    ]
}
```
 

### Get user info and GetAllUsers

```http://localhost/API/xurzInr5Gb4bwDm.1.Admin/UserInfo/1```

Allways ```apikey.user_id.Role```

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
```http://localhost/API/j1MUXD1E3Byx3Ml.1.Admin/GetOneCategories/1```

### get all categories
```http://localhost/API/j1MUXD1E3Byx3Ml.1.Admin/GetAllCategories```

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

```http://localhost/API/xurzInr5Gb4bwDm.1.Admin/CreateArticle```

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
            "user_name": "wolvi",
            "article_pic": " foto article"
            "props": [
                {
                    "prop_id": 1,
                    "prop_val": "wolvineers primer paragref",
                    "position": 1
                },
                {
                    "prop_id": 2,
                    "prop_val": "imatge.jpg",
                    "position": 2
                },
                {
                    "prop_id": 1,
                    "prop_val": "wolvineers segon paragref",
                    "position": 3
                },
                {
                    "prop_id": 2,
                    "prop_val": "http://linkwolvineers.cat/",
                    "position": 4
                },
                {
                    "prop_id": 2,
                    "prop_val": "imatge2.jpg",
                    "position": 5
                },
                {
                    "prop_id": 1,
                    "prop_val": "wolvineers tercer paragref",
                    "position": 6
                },
                {
                    "prop_id": 2,
                    "prop_val": "imatge3.jpg",
                    "position": 7
                }
            ]
        }
    
}
```

```http://localhost/API/hXa48Fx7Yut14j1.1.Admin/CreateArticle```

# comments
```
{
    "data": [
        {
            "comment_text": "comentari article 1",
            "article_id": 1,
            "user_name": "pepito"
        }
    ]
}
```
