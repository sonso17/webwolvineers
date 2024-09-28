<template>
    
        <div id="titol"> User Info </div>

        <div id="btnLogInRegister" v-if="!boolSessio">
            <button @click="goToLogIn">LogIn/Register</button>
        </div>

        <div id="userInfoGeneral" v-if="boolSessio">
            <!-- {{ userInfoJSON }} -->
            user name: <b>{{ userInfoJSON.user_name }}</b><br>
            user email: <b>{{ userInfoJSON.user_email }}</b><br>
            user role: <b>{{ userInfoJSON.user_role }}</b><br>
            user pic: <b>{{ userInfoJSON.profile_pic }}</b><br>
            user pass: <b>{{ userInfoJSON.pass }}</b><br>


            <div id="divError"></div>
        </div>
    <button @click="goToModifyUser">Modify User</button>
        
   
</template>

<script>

import axios from 'axios';

export default {
    name: "userInfo",
    props: ["userInfo"],
    data() {
        return {
            userID: "",
            apikey: "",
            user_role: "",
            userInfoJSON: {},
            boolSessio: false
        }
    },
    methods: {
        //Funció que comprova la sessió de l'usuari

        comprovarSessio() {
            if (sessionStorage.UserID && sessionStorage.APIKEY && sessionStorage.user_role) {
                this.userID = sessionStorage.UserID;
                this.apikey = sessionStorage.APIKEY;
                this.user_role = sessionStorage.user_role;

                this.getUserInfo()
                this.boolSessio = true;
                return true;
            }
            else {
                console.log("entra")
                console.log(this.userID)
                console.log(this.apikey)
                console.log(this.user_role)

                this.boolSessio = false;
                return false;
            }
        },
        //Funció que fa una peticióa l'api i rep la informació d'aquell usuari
        getUserInfo() {
            axios.get("http://localhost/API/" + this.apikey + "." + this.userID + "." + this.user_role + "/UserInfo/" + this.userID)
                .then(resultat => {
                    this.userInfoJSON = resultat.data[0]
                });
        },
        //Funció que elimina un usuari i tots els seus components
        goToLogIn() {
            this.$router.push('/login')
        },
        goToModifyUser() {
            this.$router.push('/modifyUser/' + this.userID)
        }
    },
    created() {
        this.comprovarSessio()
    }
}
</script>