<template>
  <div class="home">
    <!-- <img alt="Vue logo" src="../assets/logo.png"> -->
    <h1>Users</h1><br>
    <button @click="goToLogIn">Log In</button>
    <button @click="goToUserInfo">user Info</button>
    <button @click="goToRegister">Register</button>
    <button @click="goToModifyUser">Modify User</button>
    <h1>Articles</h1><br>

    <h1>Categories</h1><br>
    <button @click="goToAllCategories">All categories</button>
    <button @click="goToCreateCategory">Create category</button>
  </div>
</template>

<script>
// @ is an alias to /src


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

    goToAllCategories() {
      this.$router.push('/allCategories')
    },
    goToCreateCategory(){
      this.$router.push('/CreateCategory')
    },
    GoToModifyCategory(){
      this.$router.push('/ModifyCategory')
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
        console.log(this.userID)
        console.log(this.apikey)
        console.log(this.user_role)

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
