import { defineComponent, h, VNode, reactive, Teleport } from "vue";
import TooltipContent from "./components/TooltipContent.vue";
import { Placement } from "./type";

import { Trigger } from "./type";

export default defineComponent({
  render() {
    if (this.$slots.default) {
      const defaultSlot = this.$slots.default().map(this.injectMethods);
      if (this.triggerElement != null) {
        defaultSlot.push(this.injectTooltip());
      }
      return defaultSlot;
    } else return null;
  },
  setup() {
    return {};
  },
  data() {
    const TooltipProps = reactive({
      ref: "popup",
      visible: this.modelValue || this.visible,
      trigger: this.trigger,
      duration: this.duration,
      placement: this.placement,
      clickToShow: this.clickToShow,
      getTiggerElement: this.getTiggerElement,
      onClose: () => this.$emit("update:modelValue", false),
    });

    return {
      TooltipProps,
      preVisible: this.modelValue || this.visible,
      PreTrigger: "" as Trigger,
      triggerElement: null as VNode | null,
    };
  },
  props: {
    visible: {
      type: Boolean,
      default: false,
    },
    modelValue: Boolean,
    trigger: {
      type: String as () => Trigger | Array<Trigger>,
      default: "hover",
    },
    duration: {
      type: Number,
      default: 0,
    },
    clickToShow: Boolean,
    placement: {
      type: String as () => Placement,
    },
  },
  watch: {
    visible(value: boolean) {
      // this.PopupProps.visible = value;
      (this.$refs["tooltip"] as any).setTooltipVisible(value);
    },
    modelValue(value: boolean) {
      // this.PopupProps.visible = value;
      (this.$refs["tooltip"] as any).setTooltipVisible(value);
    },
  },
  methods: {
    injectTooltip() {
      return h(
        Teleport,
        { to: "#overlay" },
        h(TooltipContent, { ...this.TooltipProps }, this.$slots.content)
      );
    },

    injectMethods(vnode: VNode) {
      if (this.isComment(vnode)) return vnode;
      if (this.triggerElement == null) this.triggerElement = vnode;

      function addMethod(method: Function) {
        if (!vnode.props) vnode.props = {};

        const functionName = method.name.split(" ").pop();

        if (functionName)
          if (Array.isArray(vnode.props[functionName])) {
            vnode.props[functionName].push(method);
          } else if (typeof vnode.props[functionName] == "function") {
            vnode.props[functionName] = [vnode.props[functionName], method];
          } else {
            vnode.props[functionName] = method;
          }
      }
      if (this.trigger != "none") {
        if (this.trigger == "hover" || this.trigger.includes("hover")) {
          addMethod(this.onMouseenter);
          addMethod(this.onMouseleave);
        }

        if (this.trigger == "click" || this.trigger.includes("click")) {
          addMethod(this.onClick);
        }
      }

      return vnode;
    },

    isComment(vnode: VNode) {
      return vnode.type.toString().includes("Symbol") && vnode.children == null;
    },

    onMouseenter(event: Event) {
      (this.$refs["tooltip"] as any).onMouseenter(event);
    },
    onMouseleave(event: Event) {
      (this.$refs["tooltip"] as any).onMouseleave(event);
    },
    onClick(event: Event) {
      (this.$refs["tooltip"] as any).onClick(event);
    },

    getTiggerElement() {
      return this.triggerElement?.el as HTMLElement;
    },
  },
});