import { settings } from "./singletons/settings";
import { landing } from "./singletons/landing";
import { schoolPresentation } from "./singletons/schoolPresentation";
import { educationalProject } from "./singletons/educationalProject";
import { schoolProject } from "./singletons/schoolProject";
import { pastoralMission } from "./singletons/pastoralMission";
import { professorsPage } from "./singletons/professorsPage";
import { englishProgram } from "./singletons/englishProgram";
import { ashPage } from "./singletons/ashPage";
import { ulisPage } from "./singletons/ulisPage";
import { englishClassPage } from "./singletons/englishClassPage";
import { locationContact } from "./singletons/locationContact";
import { cafeteria } from "./singletons/cafeteria";
import { applicationForm } from "./singletons/applicationForm";
import { fees } from "./singletons/fees";
import { backToSchool } from "./singletons/backToSchool";
import { schoolBreaks } from "./singletons/schoolBreaks";
import { otherDocuments } from "./singletons/otherDocuments";
import { apelPresentation } from "./singletons/apelPresentation";
import { ogecPresentation } from "./singletons/ogecPresentation";
import { professor } from "./documents/professor";
import { newsApel } from "./documents/newsApel";
import { newsOgec } from "./documents/newsOgec";
import { newsSchool } from "./documents/newsSchool";
import { event } from "./documents/event";

export const schemaTypes = [
  // Singletons
  settings,
  landing,
  schoolPresentation,
  educationalProject,
  schoolProject,
  pastoralMission,
  professorsPage,
  englishProgram,
  ashPage,
  ulisPage,
  englishClassPage,
  locationContact,
  cafeteria,
  applicationForm,
  fees,
  backToSchool,
  schoolBreaks,
  otherDocuments,
  apelPresentation,
  ogecPresentation,
  // Documents
  professor,
  newsApel,
  newsOgec,
  newsSchool,
  event,
];
