<template>
  <div>
    <!-- Formulari per afegir o editar elements -->
    <form @submit.prevent="isEditing ? updateItem() : addOption()">
      <div id="capcaleraForm">
        <label for="ArticleTitle">Títol de l'article</label>
        <input v-model="articleTitle" type="text" id="articleTitle"
          placeholder="ex: Dani, arregla el usb-C/ Camach finalment descansa / Ianic fa el robot color arcoiris"><br>

        <label for="ArticleDescripcio">Descripció de l'article</label>
        <input v-model="articleDescripcio" type="text" id="articleDescripcio"
          placeholder="ex: Ianic fa el robot color arcoiris i a tothom li agrada molt(Ojalá) amb un unicorn i princeses">

        <div>
          <label for="visibility">Tria la visibilitat/status:</label>
          <select v-model="ArticleStatus" id="type">
            <option value="public">Visible</option>
            <option value="revision">Revisió</option>
            <option value="private">Privat</option>
          </select>
        </div>

      </div>
      <div>
        <label for="type">Tria un tipus de format per paragref:</label>
        <select v-model="selectedType" id="type" :disabled="isEditing">
          <option disabled value="">Tria una opció</option>
          <option value="1">Text</option>
          <option value="3">Enllaç</option>
          <option value="2">Imatge</option>
        </select>
      </div>

      <select v-model="ArticleCategory" id="ArticleCategorySelect">
        <option v-for="(ArtCat, i) in categoriesInfoJSON" :key="i" :value="ArtCat.category_id">
          {{ ArtCat.category_name }}
        </option>

      </select>
      
      <!-- Input dinàmic segons l'opció seleccionada -->
      <div v-if="selectedType === '1'">
        <label for="textInput">Introdueix el text:</label>
        <input v-model="inputValue" type="text" id="textInput" placeholder="Escriu un text" />
      </div>

      <div v-if="selectedType === '3'">
        <label for="linkInput">Introdueix l'URL de l'enllaç:</label>
        <input v-model="inputValue" type="url" id="linkInput" placeholder="https://exemple.com" />
      </div>

      <div v-if="selectedType === '2'">
        <label for="imageInput">Introdueix l'URL de la imatge:</label>
        <input v-model="inputValue" type="url" id="imageInput" placeholder="https://exemple.com/imatge.jpg" />
      </div>

      <!-- Botó d'afegir o actualitzar -->
      <button type="submit">{{ isEditing ? 'Actualitzar' : 'Afegir' }}</button>
      <button type="button" v-if="isEditing" @click="cancelEdit">Cancel·lar</button>
    </form>


    <!-- Llista d'elements creats amb botons d'edició -->
    <div>
      <h2>Resum article:</h2>

      <div v-for="(item, index) in items" :key="index">
        <div v-if="item.type === '1'">{{ item.value }}</div>
        <div v-if="item.type === '3'">
          <a :href="item.value" target="_blank">{{ item.value }}</a>
        </div>
        <div v-if="item.type === '2'">
          <img :src="item.value" alt="Imatge" style="max-width: 200px;" />
        </div>

        <!-- Botó per editar i eliminar -->
        <button @click="editItem(index)">Editar</button>
        <button @click="removeItem(index)">Eliminar</button>
      </div>
    </div>
    <button id="btnCrearArticle" @click="EnviarArticle">Crear article</button>
  </div>
</template>

<script>
import axios from 'axios';
// import ArticleCategoryList from '@/components/ArticleCategoryList.vue';

export default {
  data() {
    return {
      articleTitle: "",
      articleDescripcio: "",
      contaPosicio: 1,
      apikey: "",
      userID: "",
      user_role: "",
      ArticleStatus: "",
      ArticleCategory: "",
      categoriesInfoJSON: [],
      selectedType: "", // Emmagatzema el tipus seleccionat (text, link, imatge)
      inputValue: "", // Valor de l'input que l'usuari introdueix
      items: [], // Llista d'elements creats
      isEditing: false, // Indicador si està en mode edició
      currentIndex: null // Index de l'element que s'està editant
    };
  },
  methods: {
    // Afegir un nou element
    addOption() {

      if (this.inputValue) {
        this.items.push({
          type: this.selectedType,
          value: this.inputValue,
          position: this.contaPosicio++
        });
        this.resetForm();
      }
    },
    // Eliminar un element
    removeItem(index) {
      this.items.splice(index, 1);
    },
    // Editar un element existent
    editItem(index) {
      const item = this.items[index];
      this.selectedType = item.type;
      this.inputValue = item.value;
      this.currentIndex = index;
      this.isEditing = true;
    },
    // Actualitzar un element editat
    updateItem() {
      if (this.inputValue) {
        this.items[this.currentIndex] = {
          type: this.selectedType,
          value: this.inputValue
        };
        this.resetForm();
      }
    },
    // Cancel·lar l'edició
    cancelEdit() {
      this.resetForm();
    },
    // Reiniciar el formulari
    resetForm() {
      this.selectedType = "";
      this.inputValue = "";
      this.isEditing = false;
      this.currentIndex = null;
    },
    EnviarArticle() {
      console.log(this.items)
      console.log(this.ArticleCategory)
    },
    getCategories() {
      axios.get("http://localhost/API/GetAllCategories")
        .then(resultat => {
          this.categoriesInfoJSON = resultat.data
        });
    },
    getUserName() {

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
        this.boolSessio = false;
        return false;
      }
    }
  },
  created() {
    this.comprovarSessio();
    this.getCategories();
  }

};
</script>

<style scoped>
form {
  margin-bottom: 20px;
}
</style>