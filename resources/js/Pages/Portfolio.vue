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
  faSass,
  faBootstrap,
  faVuejs,
  faReact,
  faPhp,
  faLaravel,
  faGit,
  faDocker,
  faLinux,
} from "@fortawesome/free-brands-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { Head } from "@inertiajs/vue3";

library.add(
  faGithub,
  faHtml5,
  faCss3Alt,
  faJs,
  faNodeJs,
  faSass,
  faBootstrap,
  faVuejs,
  faReact,
  faPhp,
  faLaravel,
  faGit,
  faDocker,
  faLinux
);
const skills = ref({
  Frontend: [
    { name: "HTML 5", icon: faHtml5 },
    { name: "CSS 3", icon: faCss3Alt },
    { name: "Sass", icon: faSass },
    { name: "Bootstrap 5", icon: faBootstrap },
    { name: "Tailwind CSS" },
    { name: "JavaScript", icon: faJs },
    { name: "React.js", icon: faReact },
    { name: "Vue 3", icon: faVuejs },
    { name: "Inertia.js" },
    { name: "Cypress" },
    { name: "TypeScript" },
  ],
  Backend: [
    { name: "PHP 8", icon: faPhp },
    { name: "Node.js", icon: faNodeJs },
    { name: "Laravel 11", icon: faLaravel },
    { name: "MySQL - MariaDB" },
    { name: "MongoDB" },
    { name: "PostgreSQL" },
  ],
  Other: [
    { name: "Git", icon: faGit },
    { name: "GitHub", icon: faGithub },
    { name: "Docker", icon: faDocker },
    { name: "Linux", icon: faLinux },
    { name: "AWS" },
    { name: "Google Cloud" },
  ],
});

const softSkills = ref({
  "Soft Skills": ["Communication", "Teamwork", "Problem-solving"],
});

AOS.init();

const projects = ref([
  {
    title: "E-Commerce",
    description:
      "This is an E-commerce project that I developed using Vue.js and Laravel. It features a user-friendly interface with a responsive design that works well on both desktop and mobile devices. The application includes features such as user authentication, product listing, shopping cart functionality, and secure payment processing. It also has an admin panel for managing products, orders, and users.",
    link: "https://github.com/Pejoal/ecommerce",
    liveLink: "https://pejoal.live/home",
    image: "docs/images/Screenshot2.png",
  },
  {
    title: "Appointments App",
    description:
      "This app was designed to streamline appointment scheduling and coordination for a driving school. It aimed to enhance efficiency and provide a seamless experience for both instructors and students",
    link: "https://github.com/Pejoal/Termin",
    liveLink: "https://pejoal.live",
    image: "images/appointments.png",
  },
  {
    title: "Cards Game",
    description:
      "A multiplayer card game with features such as card deck management (shuffling, dealing, etc.), animations for card movements, responsive design for different devices, and real-time playing. Developed using Laravel, Inertia, Vue 3, TailwindCSS, Docker, and MySQL.",
    link: "https://github.com/Pejoal/Game",
    liveLink: "https://pejoal.live",
    image: "images/game1.png",
  },
  {
    title: "Social Media App",
    description:
      "A social media application developed using Vue.js and Laravel. The app includes features like user authentication, real-time chat, post creation and management, likes, comments, and a friend system. It also includes a responsive design for both desktop and mobile devices.",
    link: "https://github.com/Pejoal/Social-Media-Platform",
    liveLink: "https://pejaol.live",
    image: "images/social_media_logo.png",
  },
]);

const languages = ref([
  { language: "Arabic", proficiency: "Mother Tongue" },
  { language: "English", proficiency: "Fluent" },
  { language: "German", proficiency: "Conversational" },
]);

const jobs = ref([
  {
    id: 1,
    title: "Full Stack Developer",
    company: "SMEXPAND",
    startDate: "March 2023",
    endDate: "December 2023",
    description:
      "Developed an Enterprise Resource Planning (ERP) and Customer Relationship Management (CRM) system. The ERP module streamlined business processes and operations, including sales, HR, and inventory management. The CRM module improved customer service, marketing, and communication strategies. Technologies used include Vue.js for the frontend, Laravel for the backend, and MySQL for the database.",
  },
  {
    id: 2,
    title: "Full Stack Developer",
    company: "Freelancer.com",
    startDate: "January 2020",
    description:
      "Worked on various projects across different domains, primarily focusing on web development and design.",
  },
  {
    id: 3,
    title: "Junior Developer",
    company: "Upwork",
    startDate: "January 2021",
    description:
      "Assisted in the development of several small to medium scale projects, gaining experience in multiple technologies.",
  },
]);

const certificates = ref([
  {
    id: 1,
    name: "German Language B1 - Goethe",
    place: "Cairo",
    date: "April 2022",
  },
]);
</script>

<template>
  <Head>
    <title>Pejoal Hanna's Portfolio</title>
  </Head>
  <section
    class="min-h-screen bg-gray-100 flex flex-col justify-center items-center overflow-x-hidden"
  >
    <div class="bg-white shadow rounded-3xl w-full sm:max-w-2xl p-2">
      <section
        class="p-6 bg-white shadow-lg rounded-lg"
        data-aos="zoom-out-left"
      >
        <h2 class="font-bold text-2xl mb-4">Pejoal Hanna (21)</h2>
        <div class="text-gray-700">
          <p class="text-sm text-gray-500 font-normal mb-2">
            Full Stack Developer
          </p>
          <p class="text-sm text-gray-500 font-normal mb-2">Rees, Germany</p>
          <p class="text-sm text-gray-500 font-normal mb-2">
            Languages: English, German, Arabic
          </p>
          <p class="text-sm text-gray-500 font-normal mb-2">
            Education: Bachelor's Degree in Computer Science
          </p>
          <p class="text-sm text-gray-500 font-normal mb-2">
            Hobbies: Coding, Reading, Traveling
          </p>
        </div>
        <section class="text-base leading-6 font-bold sm:text-lg">
          <p>
            Contact me at
            <a href="mailto:pejoal.official@gmail.com" class="text-blue-500"
              >pejoal.official@gmail.com</a
            >
          </p>
        </section>
      </section>

      <section class="border-t my-2 text-gray-700">
        <h2 class="font-semibold text-xl pb-2">Projects</h2>
        <section
          v-for="project in projects"
          :key="project.title"
          class="border rounded-lg shadow-xl p-6 mb-6 bg-white"
          data-aos="fade-up"
          data-aos-duration="600"
          data-aos-easing="linear"
        >
          <img
            :src="project.image"
            :alt="project.title"
            class="w-full h-64 object-cover rounded-md"
          />
          <p class="text-blue-500 text-xl font-bold mt-4">
            {{ project.title }}
          </p>
          <p class="p-2">{{ project.description }}</p>
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
      </section>

      <!-- Skills Section -->
      <hr class="h-[2px] my-2 bg-black" />

      <section class="py-4 text-base text-gray-700 sm:text-lg">
        <h2 class="font-bold text-2xl">Skills</h2>
        <div
          v-for="(skillGroup, groupName) in skills"
          :key="groupName"
          class="rounded-lg shadow-2xl p-2"
          data-aos="zoom-out"
          data-aos-duration="500"
          data-aos-easing="linear"
        >
          <h3 class="text-blue-500 font-semibold text-lg">
            {{ groupName }}
          </h3>
          <ul class="list-disc list-inside">
            <li v-for="skill in skillGroup" :key="skill" class="indent-2">
              <FontAwesomeIcon
                v-if="skill.icon"
                :icon="skill.icon"
                class="mr-1"
              />
              {{ skill.name }}
            </li>
          </ul>
        </div>
      </section>

      <!-- Soft Skills Section -->
      <section
        class="py-4 text-base text-gray-700 sm:text-lg shadow-xl rounded-lg p-4"
        data-aos="zoom-in"
      >
        <div v-for="(skillGroup, groupName) in softSkills" :key="groupName">
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
      <hr class="h-[2px] my-2 bg-black" />
      <section
        class="py-4 text-base text-gray-700 sm:text-lg shadow-xl rounded-lg p-4"
        data-aos="flip-left"
        data-aos-easing="ease-out-cubic"
        data-aos-duration="500"
      >
        <h2 class="font-bold text-2xl">Languages</h2>
        <ul>
          <li
            class="relative"
            v-for="language in languages"
            :key="language.language"
          >
            <div
              class="absolute h-full border-2 w-[1px] border-red-500 left-0"
            ></div>
            <p class="pl-4">
              {{ language.language }}: {{ language.proficiency }}
            </p>
          </li>
        </ul>
      </section>

      <!-- Jobs Section -->
      <hr class="h-[2px] my-2 bg-black" />
      <section
        class="text-base text-gray-700 sm:text-lg shadow-xl rounded-lg p-4"
      >
        <h2 class="font-bold text-2xl">Jobs / Experience</h2>
        <div
          v-for="job in jobs"
          :key="job.id"
          class="bg-white my-2 rounded shadow-2xl relative"
          data-aos="fade-down"
          data-aos-easing="linear"
          data-aos-duration="500"
        >
          <div
            class="absolute h-full border-2 w-[1px] border-blue-500 left-0"
          ></div>
          <section class="pl-4">
            <h3 class="text-xl font-semibold">{{ job.title }}</h3>
            <p class="text-blue-500">{{ job.company }}</p>
            <p class="text-gray-500">
              {{ job.startDate }} - {{ job.endDate || "Present" }}
            </p>
            <p class="p-2">{{ job.description }}</p>
          </section>
        </div>
      </section>

      <!-- Certificates Section -->
      <section
        class="p-4 text-base rounded-lg shadow-2xl text-gray-700 sm:text-lg mb-10"
        data-aos="fade-down"
      >
        <h2 class="font-bold text-2xl">Certificates</h2>
        <ul>
          <li
            class="relative"
            v-for="certificate in certificates"
            :key="certificate.id"
          >
            <div
              class="absolute h-full border-2 w-[1px] border-green-500 left-0"
            ></div>
            <section class="pl-4">
              <h3 class="text-xl font-semibold text-blue-500">
                {{ certificate.name }}
              </h3>
              <p class="text-gray-500 indent-2">
                {{ certificate.place }}
                -
                {{ certificate.date }}
              </p>
            </section>
          </li>
        </ul>
      </section>
      <div>
        <a
          href="Pejoal CV.pdf"
          download
          class="mt-2 inline-block px-6 py-2 text-xs font-medium leading-6 text-center text-white uppercase transition bg-blue-700 rounded shadow ripple hover:shadow-lg hover:bg-blue-800 focus:outline-none"
        >
          Download CV
        </a>
        <iframe
          src="Pejoal CV.pdf"
          class="h-[400px] w-full"
          frameborder="1"
        ></iframe>
      </div>
    </div>
  </section>
</template>
