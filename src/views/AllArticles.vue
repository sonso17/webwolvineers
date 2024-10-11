<template>
    <div>
        <h1>AllArticles (Només Admin)</h1>
        <h2>Condicions per articles segons l'estat de la sessió:</h2>
        
        <!-- Mostrar un missatge si no hi ha articles -->
        <div v-if="!ArticlesJSON.length">
            <p>No hi ha articles per mostrar.</p>
        </div>
        
        <!-- Mostrar els articles -->
        <div id="groupArticles">
            <ArtArticle v-for="(article, index) in filteredArticles" :key="index" :aArt="article" />
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import ArtArticle from '@/components/artArticle.vue';

export default {
    name: "AllArticles",
    components: {
        ArtArticle // Registra el component ArtArticle
    },
    data() {
        return {
            userID: "",
            apikey: "",
            user_role: "",
            ArticlesJSON: [], // Cambiem a array per utilitzar v-for
            boolSessio: false
        }
    },
    computed: {
        filteredArticles() {
            // Filtra articles segons el rol de l'usuari
            if (!this.boolSessio) {
                // Si no hi ha sessió, només mostrar articles públics
                return this.ArticlesJSON.filter(article => article.public === true);
            } else if (this.user_role === 'user') {
                // Si és usuari, mostrar articles públics i els de l'usuari
                return this.ArticlesJSON.filter(article => article.public === true || article.userId === this.userID);
            } else if (this.user_role === 'admin') {
                // Si és admin, mostrar tots els articles
                return this.ArticlesJSON;
            }
            return []; // Retorna un array buit per defecte
        }
    },
    methods: {
        // Funció que comprova la sessió de l'usuari
        comprovarSessio() {
            if (sessionStorage.UserID && sessionStorage.APIKEY && sessionStorage.user_role) {
                this.userID = sessionStorage.UserID;
                this.apikey = sessionStorage.APIKEY;
                this.user_role = sessionStorage.user_role;

                this.getAllArticles();
                this.boolSessio = true;
            } else {
                this.boolSessio = false;
            }
        },
        // Funció que fa una petició a l'API i rep la informació d'aquell usuari
        getAllArticles() {
            axios.get(`http://localhost/API/${this.apikey}.${this.userID}.${this.user_role}/SelectAllArticles`)
                .then(resultat => {
                    this.ArticlesJSON = resultat.data; // Assegura't que el servidor retorna un array
                })
                .catch(error => {
                    console.error('Error al obtenir articles:', error); // Maneig d'errors
                });
        }
    },
    created() {
        this.comprovarSessio(); // Comprova la sessió quan el component es crea
    }
}
</script>

<style scoped>

</style>
