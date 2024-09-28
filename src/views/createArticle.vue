<template>
    <div>
      <!-- Formulari per afegir o editar elements -->
      <form @submit.prevent="isEditing ? updateItem() : addOption()">
        <div>
          <label for="type">Tria un tipus:</label>
          <select v-model="selectedType" id="type" :disabled="isEditing">
            <option disabled value="">Tria una opció</option>
            <option value="text">Text</option>
            <option value="link">Enllaç</option>
            <option value="image">Imatge</option>
          </select>
        </div>
  
        <!-- Input dinàmic segons l'opció seleccionada -->
        <div v-if="selectedType === 'text'">
          <label for="textInput">Introdueix el text:</label>
          <input v-model="inputValue" type="text" id="textInput" placeholder="Escriu un text" />
        </div>
  
        <div v-if="selectedType === 'link'">
          <label for="linkInput">Introdueix l'URL de l'enllaç:</label>
          <input v-model="inputValue" type="url" id="linkInput" placeholder="https://exemple.com" />
        </div>
  
        <div v-if="selectedType === 'image'">
          <label for="imageInput">Introdueix l'URL de la imatge:</label>
          <input v-model="inputValue" type="url" id="imageInput" placeholder="https://exemple.com/imatge.jpg" />
        </div>
  
        <!-- Botó d'afegir o actualitzar -->
        <button type="submit">{{ isEditing ? 'Actualitzar' : 'Afegir' }}</button>
        <button type="button" v-if="isEditing" @click="cancelEdit">Cancel·lar</button>
      </form>
  
      <!-- Llista d'elements creats amb botons d'edició -->
      <div>
        <h2>Elements creats:</h2>
        <ul>
          <li v-for="(item, index) in items" :key="index">
            <div v-if="item.type === 'text'">{{ item.value }}</div>
            <div v-if="item.type === 'link'">
              <a :href="item.value" target="_blank">{{ item.value }}</a>
            </div>
            <div v-if="item.type === 'image'">
              <img :src="item.value" alt="Imatge" style="max-width: 200px;" />
            </div>
  
            <!-- Botó per editar i eliminar -->
            <button @click="editItem(index)">Editar</button>
            <button @click="removeItem(index)">Eliminar</button>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
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
            value: this.inputValue
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
      }
    }
  };
  </script>
  
  <style scoped>
  form {
    margin-bottom: 20px;
  }
  </style>
  