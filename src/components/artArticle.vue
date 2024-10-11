<template>
    <div class="aArt" @click="goToArticle">
        <h3>{{ aArt.article_title }}</h3>
        <p v-if="aArt.descripcio">{{ aArt.descripcio }}</p>
        <p v-else>No hi ha descripció disponible.</p>
    </div>
</template>

<script>
export default {
    name: "artArticle",
    props: ["aArt"],
    methods: {
        goToArticle() {
            if (this.aArt && this.aArt.article_id) {
                this.$router.push("/getArticle/" + this.aArt.article_id);
            }
        }
    }
}
</script>

<style>
.aArt {
    position: relative;
    border: 1px solid black;
    padding: 2em 1em;
    margin: 1em;
    height: 200px;
    width: 300px;
    text-align: center;
    border-radius: 20px;
    display: inline-block;
    background: rgb(2, 0, 36);
    background: linear-gradient(90deg, #26580f 0%, rgb(108, 209, 124) 51%, #26580f 100%);
    cursor: pointer;
    transition: all 250ms ease;
}

.aArt:hover {
    top: -10px;
    left: -10px;
    box-shadow: 15px 15px 15px lightgrey;
}
</style>
