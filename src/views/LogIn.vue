<!-- 
      --Vista LogIn--

      Propietats que rep:

      Emits que fa:
      - LogInOk objecte amb les variables de log in que ha retornat l'api

      Funcionalitat del component:
      - Fa una petició post amb el correu i contrassenya de l'usuari que desitja iniciar sessió i retorna si ha trobat o no l'usuari al backend

-->

<template>
    <div id="contenidor">
        <h1>Log In</h1>
        <label class="labelLogin" for="emailUsuari">Email</label>
        <br>
        <input class="inputlogin" id="emailUsuariinput" v-model="emailUsuari" type="email" name="emailUsuari">
        <br>
        <br>
        <label class="labelLogin" for="passwd">Password</label>
        <br>
        <input class="inputlogin" type="password" id="passwdinput" v-model="passwd" name="passwd">
        <br>
        <br>
        <button id="buttonlogin" @click="enviarDadesLogIn()" :capcalera-c="isLogin" value="Log In">Log in</button>
        <button id="buttonregister" @click="goToRegister()">Register</button>
        <div id="divError"></div>
    </div>
</template>
<script>
import axios from 'axios';

export default {
    name: 'logIn',
    emits: ["logInOk"],

    data() {
        return {
            emailUsuari: "",
            passwd: "",
            isLogin: false,
            userID: "",
            apikey: "",
            user_role: "",
            user_name: "",
            user_pic: ""
        }
    },
    methods: {
        // funció que s'encarrega d'enviar les dades POST a l'API
        enviarDadesLogIn() {
            // Capturar els valors dels camps d'entrada
            this.emailUsuari = document.getElementById("emailUsuariinput").value;
            this.passwd = document.getElementById("passwdinput").value;

            // Enviar dades a l'API mitjançant una petició POST
            axios.post('http://localhost/API/LogIn', {
                data: [
                    {
                        "UserEmail": this.emailUsuari,
                        "pass": this.passwd
                    }
                ]
            }
            ).then((response) => {
                // Si la resposta és positiva, guardar informació a sessionStorage
                sessionStorage.setItem('UserID', response.data[0].user_id);
                sessionStorage.setItem('APIKEY', response.data[0].APIKEY);
                sessionStorage.setItem('user_role', response.data[0].user_role);
                sessionStorage.setItem('user_name', response.data[0].user_name);


                // Actualitzar l'estat del component amb les dades de resposta
                this.userID = response.data[0].user_id;
                this.apikey = response.data[0].APIKEY;
                this.user_role = response.data[0].user_role;
                this.user_name = response.data[0].user_name;
                // Emetre un esdeveniment de log in correcte
                this.$emit("logInOk", { userID: this.userID, apikey: this.apikey, role: this.user_role, user_name: this.user_name });

                // Actualitzar l'estat d'inici de sessió
                this.isLogin = true;

                // Redirigir l'usuari a la ruta principal
                this.$router.push('/');
            }).catch(error => {
                // Capturar l'error i mostrar el missatge d'error a la pàgina
                const message = error.response.data;
                document.getElementById("divError").innerHTML = message;
                console.log(`Error message: ${message}`);
            });
        }
    },
    goToRegister() {
        this.$router.push('/register')
    }

}

</script>

<style scoped>
#contenirdor {
    height: 100%;
}

#buttonlogin {
    background-color: #00ff4c;
    color: black;
}

#buttonlogin:hover {
    background-color: #00964b;
}

#buttonregister {
    color: black;
}
</style>