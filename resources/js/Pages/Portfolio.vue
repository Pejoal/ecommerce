<script setup>
import { ref } from "vue";
import AOS from "aos";
import "aos/dist/aos.css";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faGithub,
  faHtml5,
  faCss3Alt,
  faJs,
  faNodeJs,
} from "@fortawesome/free-brands-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(faGithub, faHtml5, faCss3Alt, faJs, faNodeJs);

AOS.init();

const projects = ref([
  {
    title: "E-commerce",
    description: "E-commerce project",
    link: "https://github.com/Pejoal/ecommerce",
    liveLink: "https://user.github.io/project1",
    image: "logo.jpg",
  },
  {
    title: "Project 2",
    description: "This is a description of Project 1.",
    link: "https://github.com/user/project1",
    liveLink: "https://user.github.io/project1",
    image: "logo.jpg",
  },
  {
    title: "Project 3",
    description: "This is a description of Project 1.",
    link: "https://github.com/user/project1",
    liveLink: "https://user.github.io/project1",
    image: "logo.jpg",
  },
]);

const languages = ref([
  { language: "Arabic", proficiency: "Mother Tongue" },
  { language: "English", proficiency: "Fluent" },
  { language: "German", proficiency: "Conversational" },
]);

const jobs = ref(["Job 1", "Job 2", "Job 3"]);
const certificates = ref(["Certificate 1", "Certificate 2", "Certificate 3"]);

const techSkills = ref({
  Frontend: [
    { name: "HTML", icon: faHtml5 },
    { name: "CSS", icon: faCss3Alt },
    { name: "JavaScript", icon: faJs },
  ],
  Backend: [{ name: "Node.js", icon: faNodeJs }],
});

const skills = ref({
  "Other Technical Skills": ["Git", "Docker", "AWS"],
  "Soft Skills": ["Communication", "Teamwork", "Problem-solving"],
});
</script>

<template>
  <section
    class="min-h-screen bg-gray-100 flex flex-col justify-center items-center"
  >
    <div class="bg-white shadow rounded-3xl w-full sm:max-w-2xl p-2">
      <section class="p-6 bg-white shadow-lg rounded-lg">
        <h2 class="font-bold text-2xl mb-4">Pejoal Hanna (21)</h2>
        <div class="text-gray-700">
          <p class="text-sm text-gray-500 font-normal mb-2">
            Full Stack Developer
          </p>
          <p class="text-sm text-gray-500 font-normal mb-2">Rees, Germany</p>
          <p class="text-sm text-gray-500 font-normal">Full Stack Developer</p>
        </div>
      </section>

      <section class="border-t my-2 text-gray-700" data-aos="fade-up">
        <h2 class="font-semibold pb-2">Projects</h2>
        <section
          v-for="project in projects"
          :key="project.title"
          class="border rounded-lg shadow-lg p-6 mb-6 bg-white"
        >
          <img
            :src="project.image"
            :alt="project.title"
            class="w-full h-64 object-cover rounded-md"
          />
          <p class="text-blue-500 text-xl font-bold mt-4">
            {{ project.title }}
          </p>
          <p class="mt-2">{{ project.description }}</p>
          <div class="mt-4">
            <a
              :href="project.link"
              class="inline-block text-blue-500 hover:text-blue-700 mr-4"
            >
              <FontAwesomeIcon :icon="faGithub" class="mr-1" />
              GitHub
            </a>
            <a
              :href="project.liveLink"
              class="inline-block text-blue-500 hover:text-blue-700"
            >
              Live
            </a>
          </div>
        </section>

        <section class="pt-4 text-base leading-6 font-bold sm:text-lg">
          <p>
            Contact me at
            <a href="mailto:pejoal.official@gmail.com" class="text-blue-500"
              >pejoal.official@gmail.com</a
            >
          </p>
        </section>
      </section>

      <section class="border-t">
        <!-- Tech Skills Section -->
        <section
          class="py-4 text-base text-gray-700 sm:text-lg"
          data-aos="zoom-out"
        >
          <h2 class="font-bold text-2xl">Skills</h2>
          <div v-for="(skillGroup, groupName) in techSkills" :key="groupName">
            <h3 class="text-blue-500 font-semibold text-lg">
              {{ groupName }}
            </h3>
            <ul class="list-disc list-inside">
              <li v-for="skill in skillGroup" :key="skill" class="indent-2">
                <FontAwesomeIcon :icon="skill.icon" class="mr-1" />
                {{ skill.name }}
              </li>
            </ul>
          </div>
        </section>

        <!-- Skills Section -->
        <section
          class="py-4 text-base text-gray-700 sm:text-lg"
          data-aos="zoom-in"
        >
          <div v-for="(skillGroup, groupName) in skills" :key="groupName">
            <h3 class="text-blue-500 font-semibold text-lg">
              {{ groupName }}
            </h3>
            <ul class="list-disc list-inside">
              <li v-for="skill in skillGroup" :key="skill" class="indent-2">
                {{ skill }}
              </li>
            </ul>
          </div>
        </section>

        <!-- Languages Section -->
        <section
          class="py-4 text-base text-gray-700 sm:text-lg"
          data-aos="flip-left"
        >
          <h2 class="font-bold text-2xl">Languages</h2>
          <ul>
            <li v-for="language in languages" :key="language.language">
              {{ language.language }}: {{ language.proficiency }}
            </li>
          </ul>
        </section>

        <!-- Jobs Section -->
        <section class="py-4 text-base text-gray-700 sm:text-lg">
          <h2 class="font-bold text-2xl">Jobs</h2>
          <ul>
            <li v-for="job in jobs" :key="job">{{ job }}</li>
          </ul>
        </section>

        <!-- Certificates Section -->
        <section class="py-4 text-base text-gray-700 sm:text-lg">
          <h2 class="font-bold text-2xl">Certificates</h2>
          <ul>
            <li v-for="certificate in certificates" :key="certificate">
              {{ certificate }}
            </li>
          </ul>
        </section>
      </section>
    </div>
  </section>
</template>
