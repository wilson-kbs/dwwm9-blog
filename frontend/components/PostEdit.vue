<template>
  <div class="post-editor-container w-full">
    <h2>Publication d'article</h2>
    <div id="post-editor-image">
      <label for="post-image">Image : </label>
      <input type="file" name="image" id="post-image" />
    </div>
    <div id="post-editor-title"></div>
    <div id="post-editor-description"></div>
    <div id="post-editor-content"></div>
    <button @click="test">test</button>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import Quill from "../utils/quill";

export default defineComponent({
  setup() {
    return {};
  },
  data() {
    return {
      editorDescriptionInstance: null as Quill | null,
      editorContentInstance: null as Quill | null,
    };
  },
  methods: {
    test() {
      const toto = JSON.stringify(
        `${JSON.stringify(this.editorContentInstance.getContents())}`
      );
      console.log(toto);
      console.log(JSON.parse(JSON.parse(toto)));
    },
  },
  mounted() {
    let optionsDescription = {
      syntax: true,
      modules: {
        toolbar: false,
      },
      placeholder: "Decription de l'aricle",
      readOnly: false,
      theme: "snow",
    };

    this.editorDescriptionInstance = new Quill(
      "#post-editor-description",
      optionsDescription
    );

    let optionsContent = {
      syntax: true,
      modules: {
        toolbar: [
          [{ font: [] }, { size: [] }],
          ["bold", "italic", "underline", "strike"],
          [{ color: [] }, { background: [] }],
          [{ script: "super" }, { script: "sub" }],
          [{ header: "1" }, { header: "2" }, "blockquote", "code-block"],
          [
            { list: "ordered" },
            { list: "bullet" },
            { indent: "-1" },
            { indent: "+1" },
          ],
          ["direction", { align: [] }],
          ["link", "image", "video", "formula"],
          ["clean"],
        ],
      },
      placeholder: "Contenue de l'article",
      readOnly: false,
      theme: "snow",
    };
    this.editorContentInstance = new Quill(
      "#post-editor-content",
      optionsContent
    );
  },
});
</script>

<style lang="scss" scoped>
.post-editor-container {
}
.post-editor-container {
  // max-height: 100;

  h2 {
    font-size: 5vmin;
  }
}

#post-editor-description {
  height: 200px;
}

#post-editor-content {
  height: 500px;
  /* width: 500px; */
}
</style>