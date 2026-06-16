import { groq } from "next-sanity";

// Singleton fetcher helper — all singletons use their type name as _id
export const singletonQuery = (type: string) =>
  groq`*[_type == "${type}"][0]`;

export const professorsQuery = groq`
  *[_type == "professor"] | order(order asc, name asc) {
    _id, name, role, photo, bio
  }
`;

export const newsApelQuery = groq`
  *[_type == "newsApel"] | order(publishedAt desc) {
    _id, title, publishedAt, coverImage, excerpt
  }
`;

export const newsApelByIdQuery = groq`
  *[_type == "newsApel" && _id == $id][0]
`;

export const newsOgecQuery = groq`
  *[_type == "newsOgec"] | order(publishedAt desc) {
    _id, title, publishedAt, coverImage, excerpt
  }
`;

export const newsOgecByIdQuery = groq`
  *[_type == "newsOgec" && _id == $id][0]
`;

export const newsSchoolQuery = groq`
  *[_type == "newsSchool"] | order(publishedAt desc) {
    _id, title, publishedAt, coverImage, excerpt
  }
`;

export const newsSchoolPreviewQuery = groq`
  *[_type == "newsSchool"] | order(publishedAt desc) [0...3] {
    _id, title, publishedAt, coverImage, excerpt
  }
`;

export const newsSchoolByIdQuery = groq`
  *[_type == "newsSchool" && _id == $id][0]
`;

export const eventsQuery = groq`
  *[_type == "event"] | order(startDate asc) {
    _id, title, startDate, endDate, location, coverImage, excerpt
  }
`;

export const eventByIdQuery = groq`
  *[_type == "event" && _id == $id][0]
`;
