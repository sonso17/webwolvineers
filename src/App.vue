<template>
  <capcalera-c :userID="userID" :apikey="apikey" :role="user_role" :user_name="user_name"  @logOut="notifica"/> <!--l'emit logout vé de la capçalera-->
  <div id="contenidorGeneral">
    <router-view @logInOk="notifica"/><!--l'emit login vé de la vista log in-->
  </div>

  <peuPagina />
</template>
<script>
import CapcaleraC from './components/capcaleraC.vue';
import peuPagina from './components/peuPagina.vue';
export default {
  name: "App",
  components: { CapcaleraC, peuPagina },
  data() {
    return {
      userID: "",
      apikey: "",
      user_role: "",
      user_name: "",
      boolSessio: false
    }
  },
  methods: {
    notifica(logInDades) {
      this.userID = logInDades.userID
      this.apikey = logInDades.apikey
      this.user_role = logInDades.role
      this.user_name = logInDades.user_name
    },
    
      comprovarSessio() {
            if (sessionStorage.UserID && sessionStorage.APIKEY && sessionStorage.user_role) {
                this.userID = sessionStorage.UserID;
                this.apikey = sessionStorage.APIKEY;
                this.user_role = sessionStorage.user_role;
                this.user_name = sessionStorage.user_name;
                this.boolSessio = true;
                var logInDades = { userID: this.userID, apikey: this.apikey, role: this.user_role, user_name: this.user_name }
                // console.log("entra")

                this.notifica(logInDades)
            }
            else {
                // console.log("entra")
                this.boolSessio = false;
            }
        }
  },
  created(){
    this.comprovarSessio()
  }
}

</script>

<style>
body {
  /* background-color: #86dc3d; */
  margin: 0;
  height: 100%;
}

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  background-color: pink;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}

/*ESTILS COMPONENTS INDIVIDUALS EX BUTONS */
button{
  background-color: #3ddcdc;
}
/*FI ESTICLS COMPONENTS INDIVIDUALS  */

/*ESTILS CAPCALERA*/
#generalHeader {
  position: relative;
  display: flex;
  flex-direction: row;
  justify-content: space-around;
  background: #26580f;
  color: white;
  font-size: 25px;
  align-items: center;
  height: 120px;
}

#capLogo {
  position: relative;
  height: 90px;
  width: 171px;
  font-size: 25px;
}
#capLogo:hover {
  cursor: pointer;
}

#capNomP {
  position: relative;
  height: 100%;
  width: 250px;
  left: 15%;
}

.btLogIn {
  position: relative;
}

#UserInfo {
  position: relative;
  height: 100%;
  width: 250px;
  font-size: 25px;
  display: flex;
  justify-content: center;
  text-align: center;
  align-items: center;
  align-content: center;
  justify-content: space-around;
}

.logout {
  width: 30px;
  height: 30px;
}

.logout:hover {
  cursor: pointer;
}

/*FI ESTILS CAPCALERA */

</style>
