<!-- 
      --Component peuPàgina--

      Propietats que rep:

      Funcionalitat del component:
      - És el peu de pàgina i la seva funció és fer maco

-->

<template>
    <div id="peuPaginaGeneral">
        <div id="peuPaginaC"><a href="https://creativecommons.org/licenses/by-sa/4.0/">CC BY-SA</a>  OwnTainer</div>
        <div id="peuPaginaC" @click="whoAmIRedirect()">Created by Aleix Poch</div>
    </div>
</template>

<script>
export default {
    name: "peuPagina",
    methods: {
        whoAmIRedirect() {
            this.$router.push("/whoAmI");
        }
    }
}
</script>

<style scoped>
#peuPaginaGeneral {
    color: #86dc3d;
    font-size: 20px;
    position: relative;
    bottom: 0px;
    height: 50px;
    width: 100%;
    margin-top: auto;
    background-color: #26580f;
}
</style>