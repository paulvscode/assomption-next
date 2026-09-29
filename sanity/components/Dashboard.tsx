"use client";

import { useCurrentUser } from "sanity";
import { IntentLink } from "sanity/router";
import type { ReactNode } from "react";

export type DashboardTask = {
  title: string;
  description: string;
  intent: "edit" | "create";
  /** Document id to edit. Defaults to `type` (singletons use their type name as id). */
  id?: string;
  type: string;
  icon: ReactNode;
};

export function Dashboard({ tasks }: { tasks: DashboardTask[] }) {
  const currentUser = useCurrentUser();
  const firstName = currentUser?.name?.split(" ")[0];

  return (
    <div style={{ padding: "40px 32px", maxWidth: 720 }}>
      <p style={{ margin: 0, fontSize: 13, color: "#8B8892" }}>
        {firstName ? `Bonjour ${firstName}` : "Bonjour"}
      </p>
      <h1
        style={{
          margin: "4px 0 24px",
          fontSize: 20,
          fontWeight: 700,
          color: "#2E2C31",
        }}
      >
        Que souhaitez-vous faire aujourd&apos;hui&nbsp;?
      </h1>
      <div
        style={{
          display: "grid",
          gridTemplateColumns: "repeat(auto-fill, minmax(200px, 1fr))",
          gap: 12,
        }}
      >
        {tasks.map((task) => (
          <TaskCard key={task.title} task={task} />
        ))}
      </div>
    </div>
  );
}

function TaskCard({ task }: { task: DashboardTask }) {
  const params =
    task.intent === "edit"
      ? { id: task.id ?? task.type, type: task.type }
      : { type: task.type };

  return (
    <IntentLink
      intent={task.intent}
      params={params}
      style={{
        display: "flex",
        flexDirection: "column",
        gap: 10,
        padding: 16,
        borderRadius: 10,
        border: "1px solid #E4E2E6",
        background: "#FFFFFF",
        textDecoration: "none",
        color: "inherit",
      }}
    >
      <span
        style={{
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
          width: 32,
          height: 32,
          borderRadius: 8,
          background: "#EAF1F4",
          color: "#2C4753",
        }}
      >
        {task.icon}
      </span>
      <span>
        <span style={{ display: "block", fontSize: 13.5, fontWeight: 700, color: "#2E2C31" }}>
          {task.title}
        </span>
        <span style={{ display: "block", marginTop: 2, fontSize: 11.5, color: "#8B8892" }}>
          {task.description}
        </span>
      </span>
    </IntentLink>
  );
}

function svgIcon(children: ReactNode) {
  return (
    <svg
      viewBox="0 0 24 24"
      width="16"
      height="16"
      fill="none"
      stroke="currentColor"
      strokeWidth="1.7"
      strokeLinecap="round"
      strokeLinejoin="round"
    >
      {children}
    </svg>
  );
}

export const DashboardIcons = {
  news: svgIcon(
    <>
      <rect x="3.5" y="4.5" width="17" height="15" rx="1.5" />
      <path d="M7.5 8.5h9M7.5 12h9M7.5 15.5h5.5" />
    </>
  ),
  image: svgIcon(
    <>
      <rect x="3.5" y="4.5" width="17" height="15" rx="2" />
      <circle cx="9" cy="10" r="1.6" />
      <path d="M20 15.5l-5-4.5-4 3.5-3-2.5-4.2 3.5" />
    </>
  ),
  users: svgIcon(
    <>
      <circle cx="9" cy="9" r="3" />
      <path d="M3.5 19c0-3.3 2.5-5.5 5.5-5.5s5.5 2.2 5.5 5.5" />
      <circle cx="17" cy="8" r="2.3" />
      <path d="M15.5 13.3c2.6.2 4.5 2.2 4.9 5.2" />
    </>
  ),
  info: svgIcon(
    <>
      <circle cx="12" cy="12" r="8.3" />
      <path d="M12 11v5.5" />
      <circle cx="12" cy="8" r=".2" fill="currentColor" strokeWidth="2.4" />
    </>
  ),
  settings: svgIcon(
    <>
      <circle cx="12" cy="12" r="3.2" />
      <path d="M12 3.5v2.3M12 18.2v2.3M20.5 12h-2.3M5.8 12H3.5M17.8 6.2l-1.6 1.6M7.8 16.2l-1.6 1.6M17.8 17.8l-1.6-1.6M7.8 7.8L6.2 6.2" />
    </>
  ),
  heart: svgIcon(
    <path d="M12 20s-7.5-4.6-9.3-9.8C1.6 6.8 3.6 4 6.7 4c1.9 0 3.5 1.1 5.3 3.5C13.8 5.1 15.4 4 17.3 4c3.1 0 5.1 2.8 4 6.2C19.5 15.4 12 20 12 20z" />
  ),
  shield: svgIcon(
    <path d="M12 3.5l7 2.8v5.4c0 4.4-2.9 7.6-7 8.8-4.1-1.2-7-4.4-7-8.8V6.3l7-2.8z" />
  ),
  calendar: svgIcon(
    <>
      <rect x="3.5" y="5" width="17" height="15" rx="1.5" />
      <path d="M3.5 9.5h17M8 3.5v3M16 3.5v3" />
    </>
  ),
};
