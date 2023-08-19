import { createRouter, createWebHistory } from "vue-router";

import Home from "./views/home-page";
import Messages from "./views/messages-page";
import defaultLayout from "./layouts/side-nav-outer-toolbar";

const router = new createRouter({
  routes: [
    {
      path: "/home",
      name: "home",
      meta: {
        requiresAuth: false,
        layout: defaultLayout
      },
      component: Home
    },
    {
      path: "/messages",
      name: "messages",
      meta: {
        requiresAuth: false,
        layout: defaultLayout
      },
      component: Messages
    },
    {
      path: "/",
      redirect: "/home"
    },
    {
      path: "/recovery",
      redirect: "/home"
    },
    {
      path: "/:pathMatch(.*)*",
      redirect: "/messages"
    }
  ],
  history: createWebHistory()
});

export default router;
