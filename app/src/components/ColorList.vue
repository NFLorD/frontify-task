<template>
    <transition-group name="color-list" tag="div" class="color-list">
      <div v-for="(color, i) in colorService.colors" :key="color.id" class="wrapper">
        <div v-bind:style="{ backgroundColor: '#' + color.hex, color: computeColor(color.hex) }">
          <p>
            <a @click="deleteColor(color, i)">x</a>
            <span class="name">{{ color.name }}</span>
            <span class="hex">#{{ color.hex }}</span>
          </p>
        </div>
      </div>
    </transition-group>
</template>

<script lang="ts">
import { Component, Inject, Vue } from 'vue-property-decorator';
import Color from '../models/Color';
import ColorService from '../services/ColorService';

@Component
export default class ColorList extends Vue {
  @Inject() colorService: ColorService;

  computeColor(hex: string): string {
    // https://stackoverflow.com/a/12043228/16040429
    var rgb = parseInt(hex, 16); // convert rrggbb to decimal
    var r = (rgb >> 16) & 0xff;  // extract red
    var g = (rgb >>  8) & 0xff;  // extract green
    var b = (rgb >>  0) & 0xff;  // extract blue

    var luma = 0.2126 * r + 0.7152 * g + 0.0722 * b; // per ITU-R BT.709
    return luma < 80 ? '#ffffff' : '#000000';
  }

  deleteColor(color: Color, index: number): void {
    this.colorService.delete(color, index);
  }
}
</script>

<style scoped lang="scss">
.color-list-item {
  display: inline-block;
  margin-right: 10px;
}
.color-list-enter-active, .color-list-leave-active {
  transition: all 1s;
}
.color-list-enter, .color-list-leave-to {
  opacity: 0;
}
.color-list-enter {
  transform: translateX(30px);
}

.color-list {
  height: auto;
  width: 80%;
  max-width: 1300px;
  box-sizing: border-box;
  
  padding: 2px;
  border-radius: 25px;
  border: 3px solid #2c3e50;

  display: flex;
  flex-flow: row wrap;
  justify-content: flex-start;
  align-items: flex-start;

  .wrapper {
    box-sizing: border-box;
    padding: 2px;
    margin: 2px 0;
    width: 20%;

    div {
      border-radius: 20px;
      height: 200px;
      line-height: 200px;

      p {
        margin: 0;
        padding: 0;

        text-align: center;

        position: relative;

        a {
          position: absolute;
          top: 15px;
          right: 15px;
          border-radius: 14px;
          width: 20px;
          height: 20px;
          line-height: 20px;
          font-size: 20px;
          
          opacity: 0;
          transition: opacity 1.5s;
          cursor: pointer;
        }

        span {
          display: inline-block;
        }

        .name {
          opacity: 1;
          position: relative;
          transform: translateX(0);

          transition: opacity 1s, transform 1s, position 0s 1s;

        }

        .hex {
          opacity: 0;
          top: -9999px;
          position: absolute;
          transform: translateX(20px);

          transition: opacity 1s, transform 1s;
        }
      }

      &:hover {
        p {
          .name {
            opacity: 0;
            top: -9999px;
            position: absolute;
            transform: translateX(-20px);
          }

          a, .hex {
            opacity: 1;
          }

          .hex {
            top: 0;
            position: relative;
            transform: translateX(0);
          }
        }
      }
    }
  }
}
</style>
