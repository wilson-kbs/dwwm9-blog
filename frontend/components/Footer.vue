<template>
  <footer>
    <div ref="wave"></div>
    <div class="footer-content">
      <div
        class="
          container
          px-5
          py-8
          mx-auto
          flex
          items-center
          sm:flex-row
          flex-col
        "
      >
        <a
          class="
            flex
            title-font
            font-medium
            items-center
            md:justify-start
            justify-center
            text-green-200
          "
          ><span class="ml-3 text-3xl">{{ $store.state.appName }}</span></a
        >
        <p
          class="
            text-xl text-green-300
            sm:ml-4 sm:pl-4 sm:border-l-2 sm:border-green-200 sm:py-2 sm:mt-0
            mt-4
          "
        >
          © 2021 Kabaliserv —
          <a
            href="https://github.com/wilson-kbs"
            rel="author noopener noreferrer"
            target="_blank"
            class="text-green-100 ml-1"
            >@wilson-kbs</a
          >
        </p>
      </div>
    </div>
  </footer>
</template>

<script lang="ts">
import wavify, { Wavify } from "@kabaliserv/wavify/dist/index";
import { defineComponent, ref } from "vue";

export default defineComponent({
  setup() {
    const wave = ref<Element>();
    const wavifyInstance = ref<Wavify>();

    return {
      wave,
      wavifyInstance,
    };
  },
  mounted() {
    console.log(this.wave);
    if (this.wave) {
      this.wavifyInstance = wavify(this.wave, {
        height: 20,
        amplitude: 20,
        speed: 0.2,
        bones: 3,
        position: "bottom",
        color: "#42b883",
        autostart: true,
      });
    }
  },
});
</script>

<style lang='scss' scoped>
.footer-content {
  height: 100px;
  background: #42b883;
}
</style>