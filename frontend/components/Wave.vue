<template>
  <div :class="['wave-container', position.toLocaleLowerCase()]">
    <div ref="waveContent" class="wave-slot-content">
      <slot></slot>
    </div>
    <div ref="wave" class="wave h-56"></div>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import wavify, { Wavify } from "@kabaliserv/wavify/dist/index";
import { AppMutationTypes } from "../store/modules/app/mutation-types";

export default defineComponent({
  setup() {
    const wave = ref<Element>();
    const waveContent = ref<HTMLElement>();
    const wavifyInstance = ref<Wavify>();

    return {
      wave,
      waveContent,
      wavifyInstance,
    };
  },
  props: {
    dark: {
      type: Boolean,
      default: true,
      required: false,
    },
    heightWave: {
      type: Number,
      default: 70,
      required: false,
    },
    contentColor: {
      type: String,
      default: null,
    },
    color: {
      type: String,
      default: "red",
      required: false,
    },
    position: {
      type: String,
      default: "bottom",
      required: false,
    },
    bones: {
      type: Number,
      default: 3,
      required: false,
    },
    speed: {
      type: Number,
      default: 0.2,
      required: false,
    },
    amplitude: {
      type: Number,
      default: 20,
      required: false,
    },
    play: {
      type: Boolean,
      default: true,
    },
  },
  computed: {
    isModalActive() {
      return this.$store.state.app.isModalActive;
    },
  },
  watch: {
    play(don) {
      console.log(don);
    },
    isModalActive(val) {
      if (val && this.play) {
        this.wavifyInstance?.pause();
      } else if (!val && this.play) {
        this.wavifyInstance?.play();
      } else this.wavifyInstance?.pause();
    },
  },
  methods: {},
  mounted() {
    console.log(this.wave, "tototoototoot");
    // if (this.wave && this.waveContent) {
    this.wavifyInstance = wavify(this.wave, {
      height: this.heightWave,
      amplitude: this.amplitude,
      speed: this.speed,
      bones: this.bones,
      position: this.position,
      color: this.color,
      // autostart: this.play && !this.isModalActive,
    });
    this.waveContent.style.background = this.contentColor ?? this.color;
    if (this.play && !this.isModalActive) this.wavifyInstance.play();
    console.log(this.play, this.isModalActive);
    // }
  },
  beforeUnmount() {
    this.wavifyInstance.pause();
  },
});
</script>

<style lang="scss" scoped>
.wave-container {
  display: flex;
  &.bottom {
    flex-direction: column-reverse;
  }
  &.top {
    flex-direction: column;
  }
  &.left {
    flex-direction: row;
  }
  &.right {
    flex-direction: row-reverse;
  }
}
.wave {
  // height: 100px;
}
</style>