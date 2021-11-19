<template >
  <transition name="tooltip-view">
    <div
      v-if="prevVisible"
      v-show="tooltipVisible"
      :class="['tooltip', 'tooltip-placement-' + placement]"
      :style="{ ...tooltipStyle }"
    >
      <!-- @mouseenter="onMouseenter"
      @mouseleave="onMouseleave" -->
      <div class="tooltip-wrapper">
        <div class="tooltip-arrow"></div>
        <div class="tooltip-content">
          <div v-if="title" class="tooltip__head">{{ title }}</div>
          <div class="tooltip__body">
            <slot></slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script lang="ts">
import { defineComponent, nextTick, reactive } from "vue";
import { Placement, Trigger } from "../type";
import { requestTimeout, removeRequestTimeout } from "../utils";
interface Align {
  x: "left" | "right" | "center";
  y: "top" | "bottom" | "center";
}
export default defineComponent({
  emits: ["close"],
  setup() {
    return {};
  },
  data() {
    const tooltipStyle = reactive({
      top: null as string | null,
      left: null as string | null,
      transformOrigin: null as string | null,
    });
    const align: Align = {
      x: "center",
      y: "center",
    };
    return {
      prevVisible: this.visible ?? false,
      tooltipVisible: this.visible ?? false,
      hastooltipMouseDown: false,
      delayTimer: 0 as number, // Use for SetTimeout
      delayToHide: 200,
      mouseDownTimeout: 0,
      positionIsUpdate: false,
      elDOMRect: null as DOMRect | null,
      tooltipStyle,
      align,
      currentTrigger: null as Trigger | null,
    };
  },
  props: {
    visible: Boolean,
    title: String,
    trigger: {
      type: String as () => Trigger | Array<Trigger>,
    },
    placement: {
      type: String as () => Placement,
      default: "top",
    },
    duration: {
      type: Number,
    },
    delay: {
      type: Number,
      default: 200,
      required: false,
    },
    clickToShow: {
      type: Boolean,
      default: false,
    },
    getTiggerElement: Function,
  },
  watch: {
    visible(value: boolean) {
      this.setTooltipVisible(value);
    },
    tooltipVisible(value) {
      if (value && !this.prevVisible) this.prevVisible = true;
      if (this.triggerIs("hover")) {
        if (value) window.addEventListener("mouseover", this.onDocumentOver);
        else window.removeEventListener("mouseover", this.onDocumentOver);
      }
      if (this.triggerIs("click")) {
        if (value) window.addEventListener("click", this.onDocumentClick, true);
        else window.removeEventListener("click", this.onDocumentClick, true);
      }
      if (!value) this.$emit("close");
    },
    triggerDOMRect() {
      this.positionIsUpdate = false;
    },
  },
  methods: {
    triggerIs(name: Trigger) {
      return this.trigger == name || this.trigger?.includes(name);
    },
    onMouseenter() {
      if (this.triggerIs("hover") && !this.currentTrigger) {
        this.currentTrigger = "hover";
        this.delaySettooltipVisible(true, this.delay);
      }
    },
    onMouseleave() {
      if (this.triggerIs("hover") && this.currentTrigger == "hover") {
        this.clearDelayTimer();
        this.delaySettooltipVisible(false, 50);
      }
    },
    onClick() {
      if (!this.currentTrigger) {
        this.currentTrigger = "click";
        this.setTooltipVisible(true);
      } else {
        if (this.tooltipVisible && !this.clickToShow) {
          this.setTooltipVisible(false);
        }
      }
    },
    setTooltipVisible(visible: boolean) {
      this.clearDelayTimer();
      if (!visible && this.hastooltipMouseDown) return;
      this.tooltipVisible = visible;
      if (visible) nextTick(() => this.updateCal());
      else this.currentTrigger = null;
      // If duration is set
      if (this.duration && this.duration != 0) {
        this.delaySettooltipVisible(false, this.duration);
      }
    },
    delaySettooltipVisible(visible: boolean, delay: number) {
      if (delay != 0) {
        this.clearDelayTimer();
        this.delayTimer = requestTimeout(() => {
          this.setTooltipVisible(visible);
          this.clearDelayTimer();
        }, delay);
      } else this.setTooltipVisible(visible);
    },
    clearDelayTimer() {
      removeRequestTimeout(this.delayTimer);
    },
    onDocumentClick(event: Event) {
      if (this.tooltipVisible) {
        if (this.IftooltipHasMouseDown(event)) {
          this.hastooltipMouseDown = true;
        } else {
          this.hastooltipMouseDown = false;
          if (event.target != this.getTiggerElement!())
            this.setTooltipVisible(false);
        }
      }
    },
    onDocumentOver(event: Event) {
      if (
        this.IftooltipHasMouseDown(event) ||
        event.target == this.getTiggerElement!()
      ) {
        this.hastooltipMouseDown = true;
      } else {
        this.hastooltipMouseDown = false;
        this.delaySettooltipVisible(false, this.delayToHide);
      }
    },
    IftooltipHasMouseDown(event: Event) {
      const contain = this.isContain(
        this.$el as HTMLElement,
        event.target as HTMLElement
      );
      return contain;
    },
    isContain(parent: HTMLElement, childen: HTMLElement) {
      return parent.contains(childen);
    },
    updateCal() {
      if (!this.positionIsUpdate) {
        const el = this.$el;
        if (el instanceof HTMLElement) {
          if (this.getTiggerElement) {
            const triggerElement = this.getTiggerElement();
            const triggerDOMRect = (
              triggerElement as HTMLElement
            ).getBoundingClientRect();
            this.setNewTooltipPosition(triggerDOMRect, el, this.align);
            this.positionIsUpdate = true;
          }
        }
      }
    },
    setNewTooltipPosition(triggerRect: DOMRect, el: HTMLElement, align: Align) {
      const Position = {
        top: "0",
        left: "0",
      };
      // Vertical Position
      if (align.y == "top") {
        Position.top = `${triggerRect.y - el.offsetHeight}px`;
      } else if (align.y == "bottom") {
        Position.top = `${triggerRect.y + triggerRect.height}px`;
      } else {
        if (triggerRect.height > el.offsetHeight) {
          Position.top = `${
            triggerRect.y - (el.offsetHeight / 2 - triggerRect.height / 2)
          }px`;
        } else {
          Position.top = `${
            triggerRect.y + (triggerRect.height - el.offsetHeight) / 2
          }px`;
        }
      }
      // Horizontal Postition
      if (align.x == "left") {
        Position.left = `${triggerRect.x - el.offsetWidth}px`;
      } else if (align.x == "right") {
        Position.left = `${triggerRect.x + triggerRect.width}px`;
      } else {
        if (triggerRect.width > el.offsetWidth) {
          Position.left = `${
            triggerRect.x - (el.offsetWidth / 2 - triggerRect.width / 2)
          }px`;
        } else {
          Position.left = `${
            triggerRect.x + (triggerRect.width - el.offsetWidth) / 2
          }px`;
        }
      }
      this.tooltipStyle.top = Position.top;
      this.tooltipStyle.left = Position.left;
    },
    getAlign(placement: Placement): Align {
      const pls = placement.toLowerCase();
      const align: Align = {
        y: "center",
        x: "center",
      };
      if (pls.includes("top")) align.y = "top";
      if (pls.includes("bottom")) align.y = "bottom";
      if (pls.includes("left")) align.x = "left";
      if (pls.includes("right")) align.x = "right";
      return align;
    },
    getTranformOrigin(align: Align): string {
      // Transform Origin
      let transformOrigin = {
        top: "50%",
        left: "50%",
      };
      // Vertical Align
      if (align.y == "top") transformOrigin.top = "80%";
      else if (align.y == "bottom") transformOrigin.top = "20%";
      // Horizontal Align
      if (align.x == "left") transformOrigin.left = "80%";
      else if (align.x == "right") transformOrigin.left = "20%";
      return `${transformOrigin.left} ${transformOrigin.top}`;
    },
  },
  beforeMount() {
    this.align = this.getAlign(this.placement);
    this.tooltipStyle.transformOrigin = this.getTranformOrigin(this.align);
  },
  mounted() {
    this.updateCal();
  },
});
</script>

<style lang="scss">
.tooltip-view-enter-active,
.tooltip-view-leave-active {
  transition: transform 0.15s ease-out 0s;
}
.tooltip-view-enter-from,
.tooltip-view-leave-to {
  transform: scale(0);
}
.tooltip {
  position: absolute;
  z-index: 1000;
}
.tooltip-content {
  color: black;
  background-color: white;
  border-radius: 1rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.247);
}
.tooltip-arrow {
  position: absolute;
  height: 0.8rem;
  width: 0.8rem;
  border-style: solid;
  border-width: 0.4rem;
  //background-color: white;
}
.tooltip__body {
  padding: 0.5rem 1rem;
  font-size: 1.4rem;
}
.tooltip-placement-top {
  padding-bottom: 1rem;
  .tooltip-arrow {
    border-color: transparent #fff #fff transparent;
    box-shadow: 3px 3px 7px rgb(0, 0, 0, 7%);
    left: 50%;
    transform: translateX(-50%) rotate(45deg);
    bottom: 6px;
  }
}
.tooltip-placement-bottom {
  padding-top: 1rem;
  .tooltip-arrow {
    border-color: #fff transparent transparent #fff;
    box-shadow: -3px -3px 7px rgb(0, 0, 0, 7%);
    left: 50%;
    transform: translateX(-50%) rotate(45deg);
    top: 6px;
  }
}
</style>