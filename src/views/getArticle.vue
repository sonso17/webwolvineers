<template>
    <h1>vista prova de get Article</h1>
    <h1>{{ ArtInfoJSON.article_title }}</h1>
    <div id="capcaleraArticle">
        <div id="descripcio">
            {{ ArtInfoJSON.descripcio }}
        </div>
        <div id="imatgeArt">
            <img :src="ArtInfoJSON.article_pic">  </div>
    </div>
    <!-- {{ ArtInfoJSON }} -->

    <div id="ArticleInfo" v-for="(prop, p) in ArtInfoJSON.props" :key="p" :item="prop">
                <div v-if="prop.prop_id == 1">{{ prop.prop_val }} </div>
                <img v-if="prop.prop_id == 2" :src="prop.prop_val">
                <a v-if="prop.prop_id == 3" :href="prop.prop_val">{{ prop.prop_val }}</a>
            </div>

    Creat per: {{ ArtInfoJSON.article_user_name }}

    <div id="grupButtonsModifyDeleteComp" v-if="userID == ArtInfoJSON.user_id"><!-- -->
        <button id="updateCompBTN" >ModifyArticle</button>
        <button id="deleteCompBTN" >Delete Article</button>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'getArticle',
    props: ['id'],
    data() {
        return {
            boolSessio: false,
            apikey: "",
            userID: "",
            ArtInfoJSON: "",
            articleID: ""
        }
    },

    methods: {
        getArticle() {
            //si hi ha sessio
            
                axios.get("http://localhost/API/SelectOnePublicArticle/" + this.id + "/")
                    .then(resultat => {
                        this.ArtInfoJSON = resultat.data[0];
                        this.articleID = this.ArtInfoJSON.article_id;
                    })
                    .catch(error => {
                        const message = error.response.data;
                        document.getElementById("divError").innerHTML = message;
                        console.log(`Error message: ${message}`);
                    })
            
        },
        comprovarSessio() {
            if (sessionStorage.UserID && sessionStorage.APIKEY) {
                this.userID = sessionStorage.UserID;
                this.apikey = sessionStorage.APIKEY;
                console.log(this.userID)
                this.boolSessio = true;
                return true;
            }
            else {
                console.log("entra")
                this.boolSessio = false;
                return false;
            }
        }
    },
    created() {
        this.comprovarSessio()
        this.getArticle()
        
    }
}

</script>