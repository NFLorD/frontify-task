<template>
  <div class="home">
    <h1>Color Manager</h1>
    <div class="container">
      <ColorList/>
      <ColorForm/>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Provide, Vue } from 'vue-property-decorator';
import ColorList from '../components/ColorList.vue';
import ColorForm from '../components/ColorForm.vue';
import ColorService from '../services/ColorService';

@Component({
  components: {
    ColorList,
    ColorForm
  },
})
export default class Home extends Vue {
  @Provide() colorService = new ColorService
}
</script>

<style scoped lang="scss">
.container {
  display: flex;
  justify-content: space-evenly;
}
</style>