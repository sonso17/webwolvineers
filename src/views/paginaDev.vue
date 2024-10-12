<template>
    <div class="home">
      <!-- <img alt="Vue logo" src="../assets/logo.png"> -->
      <h1>Users</h1><br>
      <div id="ButtonsUsers">
        <button @click="goToLogIn">Log In</button>
        <button @click="goToUserInfo">user Info</button>
        <button @click="goToRegister">Register</button>
        <button @click="goToModifyUser">Modify User</button>
      </div>
      <h1>Articles</h1><br>
      <button @click="goToGetArticle">getArticle</button>
      <button @click="goToAllArticles">UserArticles</button>
      
      <div v-if="boolSessio" id="ButtonsArticles">
        <button @click="goToCreateArticle">Create Article</button>
        <button @click="goToModifyArticle">ModifyArticle</button>
      </div>
  
      <h1>Categories:</h1>
      <div v-if="boolSessio" id="ButtonsArticles">
        <button @click="goToCreateArticle">Create Categoria</button>
        <button @click="goToModifyArticle">Modificar categoria</button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'paginaInici',
    data() {
      return {
        userID: "",
        apikey: "",
        user_role: "",
        boolSessio: false
      }
    },
    methods: {
      goToLogIn() {
        this.$router.push('/login')
      },
      goToRegister() {
        this.$router.push('/register')
      },
      goToUserInfo() {
        this.$router.push('/userInfo/' + this.userID)
      },
      goToModifyUser() {
        this.$router.push('/modifyUser/' + this.userID)
      },
      goToCreateArticle() {
        this.$router.push('/CreateArticle')
      },
      goToGetArticle() {
        this.$router.push('/getArticle/1')
      }, goToModifyArticle() {
        this.$router.push('/modifyArticle/1')
      },
      goToAllArticles(){
        this.$router.push('/AllArticles')
      },
      comprovarSessio() {
        if (sessionStorage.UserID && sessionStorage.APIKEY && sessionStorage.user_role) {
          this.userID = sessionStorage.UserID;
          this.apikey = sessionStorage.APIKEY;
          this.user_role = sessionStorage.user_role;
          this.boolSessio = true;
          return true;
        }
        else {
          // console.log(this.userID)
          // console.log(this.apikey)
          // console.log(this.user_role)
  
          this.boolSessio = false;
          return false;
        }
      },
  
    },
    created() {
      this.comprovarSessio()
    }
  }
  </script>
  