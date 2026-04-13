from __future__ import annotations

import html
import re
from pathlib import Path

from reportlab.lib import colors
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import mm
from reportlab.platypus import ListFlowable, ListItem, Paragraph, SimpleDocTemplate, Spacer

SOURCE = Path("Project_Improvement_Report.md")
OUTPUT = Path("Final_Project_Report.pdf")
URL_PATTERN = re.compile(r"https?://[^\s<]+")
LINK_COLOR = "#1f4e79"


def format_link(url: str) -> str:
    # Insert soft-wrap opportunities so long links do not overflow page width.
    display = html.escape(url).replace("/", "/&#8203;").replace(".", ".&#8203;")
    href = html.escape(url, quote=True)
    return f"<font color='{LINK_COLOR}'><u><link href='{href}' color='{LINK_COLOR}'>{display}</link></u></font>"


def inline_format(text: str) -> str:
    raw = text.strip()

    chunks: list[str] = []
    start = 0
    for match in URL_PATTERN.finditer(raw):
        if match.start() > start:
            chunks.append(html.escape(raw[start:match.start()]))
        chunks.append(format_link(match.group(0)))
        start = match.end()

    if start < len(raw):
        chunks.append(html.escape(raw[start:]))

    escaped = "".join(chunks)

    # Render inline code snippets in monospace.
    def repl(match: re.Match[str]) -> str:
        return f"<font name='Courier'>{html.escape(match.group(1))}</font>"

    escaped = re.sub(r"`([^`]+)`", repl, escaped)

    return escaped


def build_pdf(source_path: Path, output_path: Path) -> None:
    text = source_path.read_text(encoding="utf-8")
    lines = text.splitlines()

    doc = SimpleDocTemplate(
        str(output_path),
        pagesize=A4,
        leftMargin=20 * mm,
        rightMargin=20 * mm,
        topMargin=18 * mm,
        bottomMargin=18 * mm,
        title="Final Project Report",
        author="Kamwanga Rahiim",
    )

    styles = getSampleStyleSheet()

    title_style = ParagraphStyle(
        "TitleStyle",
        parent=styles["Title"],
        fontName="Helvetica-Bold",
        fontSize=23,
        leading=28,
        alignment=1,
        textColor=colors.black,
        spaceAfter=8,
    )

    meta_style = ParagraphStyle(
        "MetaStyle",
        parent=styles["Normal"],
        fontName="Helvetica",
        fontSize=10,
        leading=14,
        alignment=1,
        textColor=colors.black,
        spaceAfter=1,
    )

    section_style = ParagraphStyle(
        "SectionStyle",
        parent=styles["Heading2"],
        fontName="Helvetica-Bold",
        fontSize=14,
        leading=18,
        textColor=colors.black,
        spaceBefore=12,
        spaceAfter=6,
    )

    subheading_style = ParagraphStyle(
        "SubheadingStyle",
        parent=styles["Heading3"],
        fontName="Helvetica-Bold",
        fontSize=12,
        leading=15,
        textColor=colors.black,
        spaceBefore=8,
        spaceAfter=4,
    )

    body_style = ParagraphStyle(
        "BodyStyle",
        parent=styles["Normal"],
        fontName="Helvetica",
        fontSize=11,
        leading=17,
        textColor=colors.black,
        spaceAfter=7,
    )

    bullet_style = ParagraphStyle(
        "BulletStyle",
        parent=body_style,
        leftIndent=0,
        firstLineIndent=0,
        spaceAfter=3,
    )

    story = []
    paragraph_buffer: list[str] = []
    i = 0

    def flush_paragraph() -> None:
        nonlocal paragraph_buffer
        if not paragraph_buffer:
            return
        combined = " ".join(part.strip() for part in paragraph_buffer if part.strip())
        if combined:
            story.append(Paragraph(inline_format(combined), body_style))
        paragraph_buffer = []

    while i < len(lines):
        raw = lines[i]
        stripped = raw.strip()

        if not stripped:
            flush_paragraph()
            story.append(Spacer(1, 4))
            i += 1
            continue

        if stripped == "---":
            flush_paragraph()
            story.append(Spacer(1, 7))
            i += 1
            continue

        if stripped.startswith("# "):
            flush_paragraph()
            story.append(Paragraph(inline_format(stripped[2:]), title_style))
            i += 1
            continue

        if stripped.startswith("## "):
            flush_paragraph()
            story.append(Paragraph(inline_format(stripped[3:]), section_style))
            i += 1
            continue

        if stripped.startswith("### "):
            flush_paragraph()
            story.append(Paragraph(inline_format(stripped[4:]), subheading_style))
            i += 1
            continue

        if stripped.startswith("- "):
            flush_paragraph()
            bullet_items: list[ListItem] = []
            while i < len(lines) and lines[i].strip().startswith("- "):
                content = lines[i].strip()[2:].strip()
                bullet_items.append(
                    ListItem(
                        Paragraph(inline_format(content), bullet_style),
                        leftIndent=8,
                        value="bullet",
                    )
                )
                i += 1
            story.append(
                ListFlowable(
                    bullet_items,
                    bulletType="bullet",
                    bulletFontName="Helvetica",
                    bulletFontSize=9,
                    bulletColor=colors.black,
                    leftIndent=8,
                    spaceBefore=2,
                    spaceAfter=7,
                )
            )
            continue

        if re.match(r"^\d+\.\s+", stripped):
            flush_paragraph()
            number_items: list[ListItem] = []
            while i < len(lines) and re.match(r"^\d+\.\s+", lines[i].strip()):
                content = re.sub(r"^\d+\.\s+", "", lines[i].strip())
                number_items.append(
                    ListItem(
                        Paragraph(inline_format(content), bullet_style),
                        leftIndent=8,
                    )
                )
                i += 1
            story.append(
                ListFlowable(
                    number_items,
                    bulletType="1",
                    bulletFontName="Helvetica",
                    bulletFontSize=9,
                    bulletColor=colors.black,
                    leftIndent=8,
                    spaceBefore=2,
                    spaceAfter=7,
                )
            )
            continue

        # Center metadata block before first section.
        if stripped.startswith("File Name:") or stripped.startswith("Project Title:") or stripped.startswith("Developer:") or stripped.startswith("Registration Number:") or stripped.startswith("Framework:") or stripped.startswith("Repository Link:"):
            flush_paragraph()
            story.append(Paragraph(inline_format(stripped), meta_style))
            i += 1
            continue

        paragraph_buffer.append(stripped)
        i += 1

    flush_paragraph()

    def add_footer(canvas, document):
        canvas.saveState()
        canvas.setStrokeColor(colors.black)
        canvas.setLineWidth(0.4)
        canvas.line(document.leftMargin, 14 * mm, A4[0] - document.rightMargin, 14 * mm)
        canvas.setFont("Helvetica", 9)
        canvas.setFillColor(colors.black)
        canvas.drawString(document.leftMargin, 9.5 * mm, "Campus Lost & Found Tracker - Final Project Report")
        page_label = f"Page {document.page}"
        canvas.drawRightString(A4[0] - document.rightMargin, 9.5 * mm, page_label)
        canvas.restoreState()

    doc.build(story, onFirstPage=add_footer, onLaterPages=add_footer)


if __name__ == "__main__":
    if not SOURCE.exists():
        raise SystemExit(f"Source file not found: {SOURCE}")

    build_pdf(SOURCE, OUTPUT)
    print(f"Generated PDF: {OUTPUT.resolve()}")
