<template>
  <div class="post-item-card h-96">
    <div class="flex flex-1 h-full overflow-hidden">
      <div class="item-content text-white w-2/3">
        <div class="content-header w-full">
          <router-link
            class="item-wrapper flex h-full"
            :to="'/article/' + postId.toString() + '/' + slug"
          >
            <p class="header-title mb-3" :title="title">{{ title }}</p>
          </router-link>
          <div
            class="
              header-info
              flex flex-row-reverse
              justify-between
              text-2xl
              opacity-70
            "
          >
            <div class="info-publish flex">
              <svg
                class="publish-icon h-8 mr-3"
                viewBox="0 0 448 512"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M400 64H352V12C352 5.4 346.6 0 340 0H332C325.4 0 320 5.4 320 12V64H128V12C128 5.4 122.6 0 116 0H108C101.4 0 96 5.4 96 12V64H48C21.5 64 0 85.5 0 112V464C0 490.5 21.5 512 48 512H400C426.5 512 448 490.5 448 464V112C448 85.5 426.5 64 400 64ZM48 96H400C408.8 96 416 103.2 416 112V160H32V112C32 103.2 39.2 96 48 96ZM400 480H48C39.2 480 32 472.8 32 464V192H416V464C416 472.8 408.8 480 400 480ZM148 320H108C101.4 320 96 314.6 96 308V268C96 261.4 101.4 256 108 256H148C154.6 256 160 261.4 160 268V308C160 314.6 154.6 320 148 320ZM244 320H204C197.4 320 192 314.6 192 308V268C192 261.4 197.4 256 204 256H244C250.6 256 256 261.4 256 268V308C256 314.6 250.6 320 244 320ZM340 320H300C293.4 320 288 314.6 288 308V268C288 261.4 293.4 256 300 256H340C346.6 256 352 261.4 352 268V308C352 314.6 346.6 320 340 320ZM244 416H204C197.4 416 192 410.6 192 404V364C192 357.4 197.4 352 204 352H244C250.6 352 256 357.4 256 364V404C256 410.6 250.6 416 244 416ZM148 416H108C101.4 416 96 410.6 96 404V364C96 357.4 101.4 352 108 352H148C154.6 352 160 357.4 160 364V404C160 410.6 154.6 416 148 416ZM340 416H300C293.4 416 288 410.6 288 404V364C288 357.4 293.4 352 300 352H340C346.6 352 352 357.4 352 364V404C352 410.6 346.6 416 340 416Z"
                  fill="currentColor"
                />
              </svg>
              <span class="publish-text self-center">
                {{ date }}
              </span>
            </div>
            <div class="info-author flex">
              <svg
                class="author-icon h-7 mr-3 self-center"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 448 512"
                fill="none"
              >
                <path
                  d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"
                  fill="currentColor"
                />
              </svg>
              <span class="author-text self-end clickable">
                <router-link
                  :to="`/redactor/${redactor.id}/${slugFromChar(
                    `${redactor.firstname} ${redactor.lastname}`
                  )}`"
                >
                  {{ redactor.firstname }} {{ redactor.lastname }}
                </router-link>
              </span>
            </div>
          </div>
        </div>
        <div class="post-short-content">{{ shortContent }}</div>
      </div>
      <div class="item-img h-full">
        <img class="block h-full w-full" :src="img" :alt="title" />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";
import { Redactor } from "../../interfaces";
import { AuthActionTypes } from "../../store/modules/auth/action-types";
import { AuthMutationTypes } from "../../store/modules/auth/mutation-types";
import { SlugFromChar } from "../../utils";

export default defineComponent({
  props: {
    postId: {
      type: Number,
      required: true,
    },
    title: {
      type: String,
      required: true,
    },
    shortContent: {
      type: String,
      required: true,
    },
    redactor: {
      type: Object as PropType<Redactor>,
      required: true,
    },
    slug: {
      type: String,
      required: true,
    },
    img: {
      type: String,
      required: true,
    },
    date: {
      type: String,
      required: true,
    },
  },
  computed: {
    postTile() {
      return this.title.length < 50
        ? this.title
        : this.title.substr(0, 50).concat("...");
    },
  },
  methods: {
    slugFromChar: SlugFromChar,
  },
});
</script>

<style lang="scss" scoped>
/* Clip text after 2 line */
.header-title {
  text-overflow: clip;
  overflow: hidden;
  display: -webkit-box !important;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  white-space: normal;
}
.header-title {
  font-size: 2.3vmin;
  font-weight: bold;
  text-decoration: underline;
  color: rgb(132, 226, 132);
}

.item-img {
  padding: 1rem;
  img {
    object-fit: cover;
  }
  width: 40%;
}

.item-content {
  width: 60%;
  // padding: 2rem;
  padding-right: 1rem;
}

.header-info {
  svg {
    // color: rgb(91, 247, 91);
  }
}
</style>