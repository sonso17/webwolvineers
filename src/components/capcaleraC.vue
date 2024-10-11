<template>
  <div id="generalHeader">
    <img src="@/assets/LogoFinal.png" id="capLogo" @click="GotoHome()" />

    <div id="UserInfo" v-if="userID && apikey && role && user_name">
      <button id="capNomUsuari" @click="GoToUserInfo"> {{ user_name }}</button>
      <img v-if="btnLogout" src="@/assets/logout.png" alt="" class="logout" @click="logout">
    </div>

    <button v-if="!btnLogout" @click="GoToLogIn" class="btLogIn">Log In / Register</button>

  </div>
</template>

<script>
export default {
  name: "capcaleraC",
  props: ["userID", "apikey", "role", "user_name"],
  emits: ["logOut"],
  data() {
    return {
      sessioUsuari: false,
    }
  },
  computed: {
    btnLogout() {
      return (this.userID != "" && this.apikey != "" && this.role != "" && this.user_name != "")
    }
  },
  methods: {
    /*
       Function: GotoHome

           funcio que quan es crida, t'envia a la vista principal
       */
    GotoHome() {
      this.$router.push("/");
    },
    GoToLogIn() {
      this.$router.push("/logIn");
    },
    GoToUserInfo() {
      // funció que t'envia a la pàgina de user info amb els paràmetres de l'usuari
      this.$router.push({ name: 'userInfo', params: { id: this.userID } });

    },
    /*
       Function: posardades()

           funcio que mira comproba si algú ha iniciat sessió i si és que si, guarda els valors en el data()
           
       */
    posardades() {
      if (sessionStorage.UserID && sessionStorage.APIKEY && sessionStorage.role) {
        this.sessioUsuari = true;
        return true;
      }
      else {
        return false;
      }
    },
   
    /*
    Function: logOut()

      funció que deixa buides les variables de sessió i notifica amb un emit de que ja no hi ha cap sessió activa
    */
    logout() {
      sessionStorage.clear()
      this.$emit("logOut", { userID: "", apikey: "", role: "", user_name: ""});
      this.sessioUsuari = false;
      this.$router.push("/")
    },
  },
  created() {
    this.posardades()
  },
};
</script>

<style></style>