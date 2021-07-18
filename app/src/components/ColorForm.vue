<template>
    <form @submit="createColor" class="color-form">
        <h2>New color</h2>
        <p>
          <label for="name">Name</label>
          <input
            id="name"
            type="text"
            name="name"
            required
          >
        </p>
        <p>
          <label for="hex">Color</label>
          <input
            id="hex"
            type="color"
            name="hex"
            class="form-control" 
          >
        </p>
        <p>
          <button type="submit">Create</button>
        </p>
        <p class="error" v-if="error">
          {{ error }}
        </p>
    </form>
</template>

<script lang="ts">
import { Component, Inject, Vue } from 'vue-property-decorator';
import ColorService from '../services/ColorService';

@Component
export default class ColorForm extends Vue {
  @Inject() colorService: ColorService;

  error: string;

  data() {
    return {
      error: null
    };
  }
  
  async createColor(e) {
    e.preventDefault();

    const form = document.querySelector<HTMLFormElement>('.color-form');
    if (!form.checkValidity()) {
      return;
    }

    const body = new FormData(form);
    const name = String(body.get('name'));
    const hex = String(body.get('hex')).replace('#', '');
    
    try {
      await this.colorService.create({ name, hex });
      this.error = null;
    } catch (error) {
      this.error = error.message;
    }
  }
}
</script>

<style scoped lang="scss">
.color-form {
  width: 20%;

  label {
    display: inline-block;
    width: 15%;
    text-align: right;
    margin: 2px;
  }

  input {
    display: inline-block;
    width: 47%;
    margin: 2px;
  }

  .error {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
    width: 80%;
    margin: auto;
    padding: 5px 10px;
    border-radius: 5px;
  }
}
</style>
