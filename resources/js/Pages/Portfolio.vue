<script setup>
import { ref } from "vue";
import AOS from "aos";
import "aos/dist/aos.css";

AOS.init();

const projects = ref([
  {
    title: "Project 1",
    description: "Description for Project 1",
    link: "https://github.com/Pejoal/ecommerce",
    logo: "https://github.com/Pejoal/ecommerce/logo.png",
  },
  {
    title: "Project 2",
    description: "Description for Project 2",
    link: "https://github.com/Pejoal/project2",
    logo: "https://github.com/Pejoal/project2/logo.png",
  },
  {
    title: "Project 3",
    description: "Description for Project 3",
    link: "https://github.com/Pejoal/project3",
    logo: "https://github.com/Pejoal/project3/logo.png",
  },
]);

const jobs = ref(["Job 1", "Job 2", "Job 3"]);
const certificates = ref(["Certificate 1", "Certificate 2", "Certificate 3"]);

const skills = ref({
  frontend: ["HTML", "CSS", "JavaScript", "Vue.js"],
  backend: ["Node.js", "Express.js", "MongoDB"],
  languages: ["Python", "Java", "C++"],
  otherTechnicalSkills: ["Git", "Docker", "AWS"],
  softSkills: ["Communication", "Teamwork", "Problem-solving"],
});
</script>

<template>
  <section
    class="min-h-screen bg-gray-100 flex flex-col justify-center items-center"
  >
    <div class="bg-white shadow rounded-3xl w-full sm:max-w-2xl p-2">
      <section class="flex items-center space-x-1">
        <div class="block font-semibold text-xl self-start text-gray-700">
          <h2 class="leading-relaxed">Pejoal Hanna (21)</h2>
          <p class="text-sm text-gray-500 font-normal leading-relaxed">
            Full Stack Developer
          </p>
          <p class="text-sm text-gray-500 font-normal leading-relaxed">
            Rees, Germany
          </p>
          <p class="text-sm text-gray-500 font-normal leading-relaxed">
            Full Stack Developer
          </p>
        </div>
      </section>

      <section class="divide-y divide-gray-200">
        <section
          class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7"
        >
          <section
            v-for="project in projects"
            :key="project.title"
            data-aos="fade-up"
          >
            <p class="text-blue-500">{{ project.title }}</p>
            <p>{{ project.description }}</p>
            <a :href="project.link" class="text-green-500"
              >View Project on GitHub</a
            >
          </section>
        </section>

        <section
          class="pt-6 text-base leading-6 font-bold sm:text-lg sm:leading-7"
        >
          <p>
            Contact me at
            <a href="mailto:pejoal.official@gmail.com" class="text-blue-500"
              >pejoal.official@gmail.com</a
            >
          </p>
        </section>
      </section>

      <section class="divide-y divide-gray-200">
        <!-- Skills Section -->
        <section
          class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7"
        >
          <h2 class="font-bold text-2xl">Skills</h2>
          <div v-for="(skillGroup, groupName) in skills" :key="groupName">
            <h3 class="text-blue-500 font-semibold text-lg">
              {{ groupName }}
            </h3>
            <ul class="list-disc list-inside">
              <li
                v-for="skill in skillGroup"
                :key="skill"
                class="text-green-500"
              >
                {{ skill }}
              </li>
            </ul>
          </div>
        </section>

        <!-- Jobs Section -->
        <section
          class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7"
        >
          <h2 class="font-bold text-2xl">Jobs</h2>
          <ul>
            <li v-for="job in jobs" :key="job">{{ job }}</li>
          </ul>
        </section>

        <!-- Certificates Section -->
        <section
          class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7"
        >
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
