import os
from pptx import Presentation
from pptx.util import Inches, Pt
from pptx.dml.color import RGBColor
from pptx.enum.text import PP_ALIGN
from pptx.enum.shapes import MSO_SHAPE

def create_defense_presentation():
    prs = Presentation()
    # Set 16:9 widescreen
    prs.slide_width = Inches(13.333)
    prs.slide_height = Inches(7.5)

    blank_layout = prs.slide_layouts[6]

    # Theme Colors
    COLOR_BG = RGBColor(11, 17, 32)         # Deep Navy #0b1120
    COLOR_CARD = RGBColor(22, 33, 58)       # #16213a
    COLOR_PRIMARY = RGBColor(56, 189, 248)  # Sky Blue #38bdf8
    COLOR_SECONDARY = RGBColor(129, 140, 248) # Indigo #818cf8
    COLOR_TEXT = RGBColor(248, 250, 252)    # White #f8fafc
    COLOR_MUTED = RGBColor(148, 163, 184)   # Slate #94a3b8
    COLOR_GREEN = RGBColor(52, 211, 153)    # Emerald #34d399
    COLOR_ACCENT = RGBColor(168, 85, 247)   # Purple #a855f7

    def add_background(slide):
        bg = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, 0, 0, prs.slide_width, prs.slide_height)
        bg.fill.solid()
        bg.fill.fore_color.rgb = COLOR_BG
        bg.line.fill.background()
        return bg

    def add_header(slide, title_text, category_text="مشروع هندسة البرمجيات (العملي)"):
        # Header category tag
        cat_box = slide.shapes.add_textbox(Inches(0.8), Inches(0.4), Inches(11.7), Inches(0.4))
        tf_cat = cat_box.text_frame
        tf_cat.word_wrap = True
        p_cat = tf_cat.paragraphs[0]
        p_cat.text = category_text
        p_cat.font.size = Pt(13)
        p_cat.font.bold = True
        p_cat.font.color.rgb = COLOR_PRIMARY

        # Slide Title
        title_box = slide.shapes.add_textbox(Inches(0.8), Inches(0.75), Inches(11.7), Inches(0.8))
        tf_title = title_box.text_frame
        tf_title.word_wrap = True
        p_title = tf_title.paragraphs[0]
        p_title.text = title_text
        p_title.font.size = Pt(26)
        p_title.font.bold = True
        p_title.font.color.rgb = COLOR_TEXT

    # ----------------------------------------------------
    # SLIDE 1: Title Slide
    # ----------------------------------------------------
    s1 = prs.slides.add_slide(blank_layout)
    add_background(s1)

    # Accent decorative box
    acc = s1.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(1.5), Inches(1.2), Inches(10.33), Inches(5.1))
    acc.fill.solid()
    acc.fill.fore_color.rgb = COLOR_CARD
    acc.line.color.rgb = COLOR_PRIMARY
    acc.line.width = Pt(1.5)

    tb = s1.shapes.add_textbox(Inches(2.0), Inches(1.6), Inches(9.33), Inches(4.3))
    tf = tb.text_frame
    tf.word_wrap = True

    p0 = tf.paragraphs[0]
    p0.text = "هندسة البرمجيات (العملي) - العام الجامعي 2026/2027"
    p0.font.size = Pt(15)
    p0.font.bold = True
    p0.font.color.rgb = COLOR_PRIMARY
    p0.space_after = Pt(14)

    p1 = tf.add_paragraph()
    p1.text = "منصة GradTrack لمتابعة مشاريع التخرج"
    p1.font.size = Pt(32)
    p1.font.bold = True
    p1.font.color.rgb = COLOR_TEXT
    p1.space_after = Pt(6)

    p2 = tf.add_paragraph()
    p2.text = "حوكمة المراحل (Milestones)، أتمتة الاختبارات، وتطبيق أفضل ممارسات Git & CI/CD"
    p2.font.size = Pt(16)
    p2.font.color.rgb = COLOR_MUTED
    p2.space_after = Pt(28)

    p3 = tf.add_paragraph()
    p3.text = "• إشراف المهندس القدير: ساهر الهمداني\n• إعداد وبرمجة الطالب: إلياس جمال الساده\n• إطار العمل: Laravel 11/12 (PHP 8.3) + GitHub Actions CI"
    p3.font.size = Pt(16)
    p3.font.bold = True
    p3.font.color.rgb = COLOR_GREEN

    # ----------------------------------------------------
    # SLIDE 2: Vision & Lecture 1 Core Philosophy
    # ----------------------------------------------------
    s2 = prs.slides.add_slide(blank_layout)
    add_background(s2)
    add_header(s2, "فلسفة المشروع: من المبرمج إلى مهندس البرمجيات", "الأسس الهندسية المتبعة")

    def add_card(slide, left, top, width, height, title, body, border_color=COLOR_PRIMARY):
        card = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, left, top, width, height)
        card.fill.solid()
        card.fill.fore_color.rgb = COLOR_CARD
        card.line.color.rgb = border_color
        card.line.width = Pt(1.2)

        tb = slide.shapes.add_textbox(left + Inches(0.2), top + Inches(0.2), width - Inches(0.4), height - Inches(0.4))
        tf = tb.text_frame
        tf.word_wrap = True
        p_t = tf.paragraphs[0]
        p_t.text = title
        p_t.font.size = Pt(18)
        p_t.font.bold = True
        p_t.font.color.rgb = border_color
        p_t.space_after = Pt(10)

        p_b = tf.add_paragraph()
        p_b.text = body
        p_b.font.size = Pt(14)
        p_b.font.color.rgb = COLOR_TEXT
        return card

    add_card(s2, Inches(0.8), Inches(1.8), Inches(5.6), Inches(4.8), 
             "1. عقلية مهندس البرمجيات", 
             "• لا نكتب الكود فقط، بل نبني نظاماً قابلاً للتوسع والصيانة (Maintainable & Scalable).\n\n"
             "• مهندس البرمجيات لا يسأل: 'كيف أكتب الكود فقط؟' بل يسأل أيضاً: 'لماذا أبني؟ وكيف نختبر؟'\n\n"
             "• الفهم قبل التنفيذ: البدء بالكود مباشرة يضاعف إعادة العمل لاحقاً، بينما التخطيط الجيد يمنع الأخطاء مبكراً.")

    add_card(s2, Inches(6.8), Inches(1.8), Inches(5.6), Inches(4.8), 
             "2. ركائز التقييم الست في المشروع", 
             "• وضوح الفكرة والـ Mini-SRS ونطاق العمل (In-Scope & Out-of-Scope).\n\n"
             "• قصص المستخدمين وصياغة معايير القبول (Given / When / Then).\n\n"
             "• التفكير في السيناريوهات الشاذة وحالات الحافة (Edge Cases).\n\n"
             "• دورة عمل احترافية باستخدام Git, Branches, PRs, و Issues.\n\n"
             "• خط أنابيب التكامل المستمر (GitHub Actions CI) واختبارات PHPUnit.\n\n"
             "• التوثيق الشفاف لاستخدام الذكاء الاصطناعي (AI_Log.md).", COLOR_GREEN)

    # ----------------------------------------------------
    # SLIDE 3: Problem & Proposed Solution
    # ----------------------------------------------------
    s3 = prs.slides.add_slide(blank_layout)
    add_background(s3)
    add_header(s3, "المشكلة والحل المقترح (Problem & Proposed Solution)")

    add_card(s3, Inches(0.8), Inches(1.8), Inches(5.6), Inches(4.8),
             "المشكلة الواقعية (The Problem)",
             "1. غياب المتابعة المرحلية: تراكم تسليمات المشاريع حتى نهاية الفصل دون تجزئة زمنية واضحة.\n\n"
             "2. تشتت قنوات التواصل: ضياع تعديلات وملاحظات المشرف بين الإيميل وتطبيقات المراسلة والورق.\n\n"
             "3. غياب الشفافية في التقييم: صعوبة معرفة الطالب لمستوى إنجازه الفعلي والأوزان النسبية لكل تسليم.\n\n"
             "4. عشوائية إدارة الأكواد: عدم ربط مخرجات كل مرحلة بـ Commits وفروع ومستودع GitHub رسمي.", COLOR_ACCENT)

    add_card(s3, Inches(6.8), Inches(1.8), Inches(5.6), Inches(4.8),
             "الحل الهندسي: منصة GradTrack",
             "1. تقسيم المشروع إلى مراحل (Milestones) بأوزان محددة وتواريخ استحقاق واضحة.\n\n"
             "2. بوابة رفع مخرجات تدعم روابط مستودعات GitHub، أرقام الإصدارات (v1, v2)، والتقارير.\n\n"
             "3. دورة مراجعة وتقييم للمشرف بقرارات هندسية: (Approved / Needs Revision / Rejected) مع رصد درجات.\n\n"
             "4. لوحة تحكم ذكية تحسب نسب الإنجاز التراكمية تلقائياً وتوفر سجلاً كاملاً للعمليات (Audit Log).", COLOR_GREEN)

    # ----------------------------------------------------
    # SLIDE 4: User Personas & Scope
    # ----------------------------------------------------
    s4 = prs.slides.add_slide(blank_layout)
    add_background(s4)
    add_header(s4, "المستخدمون المستهدفون ونطاق المشروع (Personas & Scope)")

    add_card(s4, Inches(0.8), Inches(1.8), Inches(3.7), Inches(4.8),
             "الطالب (Student)",
             "• الطالب: إلياس جمال الساده.\n\n"
             "• المسؤوليات:\n"
             "  - استعراض متطلبات كل مرحلة.\n"
             "  - رفع روابط GitHub والتقارير.\n"
             "  - متابعة ملاحظات المشرف وإعادة التسليم عند طلب التعديل (Revisions).", COLOR_PRIMARY)

    add_card(s4, Inches(4.8), Inches(1.8), Inches(3.7), Inches(4.8),
             "المشرف الأكاديمي (Supervisor)",
             "• المشرف: المهندس ساهر الهمداني.\n\n"
             "• المسؤوليات:\n"
             "  - جدولة مراحل المشروع وأوزانها.\n"
             "  - فحص الكود والتسليمات.\n"
             "  - رصد الدرجات وكتابة التغذية الراجعة وتحديد حالة الاعتماد.", COLOR_ACCENT)

    add_card(s4, Inches(8.8), Inches(1.8), Inches(3.7), Inches(4.8),
             "نطاق العمل (Project Scope)",
             "✅ داخل النطاق (In-Scope):\n"
             "  - مصادقة وأدوار RBAC.\n"
             "  - دورة كاملة للتسليم والمراجعة.\n"
             "  - التحقق من حالات الحافة.\n"
             "  - اختبارات آلية وتكامل CI/CD.\n\n"
             "❌ خارج النطاق (Out-of-Scope):\n"
             "  - بوابات الدفع الإلكتروني.\n"
             "  - غرف المحادثات الصوتية.", COLOR_GREEN)

    # ----------------------------------------------------
    # SLIDE 5: User Stories & Acceptance Criteria
    # ----------------------------------------------------
    s5 = prs.slides.add_slide(blank_layout)
    add_background(s5)
    add_header(s5, "قصص المستخدمين ومعايير القبول (Given / When / Then)")

    add_card(s5, Inches(0.8), Inches(1.8), Inches(5.6), Inches(4.8),
             "قصة 1: رفع تسليم المرحلة (Student Submission)",
             "• بصفتي: طالباً في المشروع (إلياس الساده).\n"
             "• أريد: رفع تسليم مرحلة مع رابط GitHub والملاحظات.\n"
             "• حتى: أتمكن من توثيق عملي وإرساله للمشرف للمراجعة.\n\n"
             "معايير القبول (Acceptance Criteria):\n"
             "✔ Given: الطالب مسجل دخول، وعضو بالمشروع، والمرحلة مفتوحة.\n"
             "✔ When: يدخل ملاحظات صالحة ورابط GitHub ويضغط تسليم.\n"
             "✔ Then: تُحفظ البيانات، وتتحول الحالة إلى Submitted، ويظهر تنبيه نجاح أخضر.")

    add_card(s5, Inches(6.8), Inches(1.8), Inches(5.6), Inches(4.8),
             "قصة 2: تقييم المشرف (Supervisor Review)",
             "• بصفتي: مشرفاً أكاديمياً (م. ساهر الهمداني).\n"
             "• أريد: مراجعة تسليم الطلاب ورصد درجة وملاحظات وتحديد القرار.\n"
             "• حتى: يعرف الطلاب مواطن القوة والضعف ويعدلوا المطلوب.\n\n"
             "معايير القبول (Acceptance Criteria):\n"
             "✔ Given: المشرف مسجل دخوله والمرحلة بحالة Submitted.\n"
             "✔ When: يرصد درجة (0-100) ويكتب ملاحظات ويختار Approved.\n"
             "✔ Then: تتحول الحالة لـ Approved وتُحسب نسبة إنجاز المشروع فورياً.", COLOR_GREEN)

    # ----------------------------------------------------
    # SLIDE 6: Edge Cases Matrix
    # ----------------------------------------------------
    s6 = prs.slides.add_slide(blank_layout)
    add_background(s6)
    add_header(s6, "معمل حالات الحافة: التفكير في السيناريوهات الشاذة (Edge Cases)")

    add_card(s6, Inches(0.8), Inches(1.8), Inches(5.6), Inches(2.3),
             "1. حالات البيانات (Data)",
             "• الحقول الفارغة: FormRequest بقاعدة min:10 و required.\n"
             "• الروابط المزيفة: فحص Regex صارم للنطاق https://github.com/.\n"
             "• الدرجات غير المنطقية: حصر القيمة بين 0 و 100 برمجياً.", COLOR_PRIMARY)

    add_card(s6, Inches(6.8), Inches(1.8), Inches(5.6), Inches(2.3),
             "2. حالات الصلاحيات (Authorization)",
             "• طالب غريب: رفض فوري برمز 403 Forbidden لمن لا ينتمي للمشروع.\n"
             "• تقييم النفس: منع الطالب من تقييم تسليمه وحصرها بالمشرف.\n"
             "• تعديل المعتمد: منع إعادة التسليم إذا كانت الحالة Approved.", COLOR_ACCENT)

    add_card(s6, Inches(0.8), Inches(4.3), Inches(5.6), Inches(2.3),
             "3. حالات الوقت والتاريخ (Time)",
             "• التسليم المتأخر: عدم حرمان الطالب بل تسجيل is_late = true مع تنبيه المشرف بعدد ساعات التأخير.\n"
             "• انتهاء الجلسة: حفظ مسودة التقرير محلياً بـ LocalStorage.", COLOR_GREEN)

    add_card(s6, Inches(6.8), Inches(4.3), Inches(5.6), Inches(2.3),
             "4. حالات النظام والتزامن (System)",
             "• النقر المزدوج (Double Click): تعطيل زر الإرسال برمجياً عبر الـ JS.\n"
             "• سلامة البيانات: استخدام DB::transaction لضمان تحديث الحالة وحفظ المراجعة كعملية ذرية واحدة.", COLOR_SECONDARY)

    # ----------------------------------------------------
    # SLIDE 7: Git Workflow & GitHub Issues
    # ----------------------------------------------------
    s7 = prs.slides.add_slide(blank_layout)
    add_background(s7)
    add_header(s7, "إدارة العمل الجماعي وسير عمل Git & GitHub")

    add_card(s7, Inches(0.8), Inches(1.8), Inches(5.6), Inches(4.8),
             "المهام الخمس الأساسية (5 Core Issues)",
             "1. Issue #1: [FEAT] نظام المصادقة والصلاحيات (Auth & RBAC).\n\n"
             "2. Issue #2: [FEAT] إدارة مشاريع التخرج وجدولة المراحل.\n\n"
             "3. Issue #3: [FEAT] بوابة رفع تسليمات المراحل وروابط GitHub.\n\n"
             "4. Issue #4: [FEAT] لوحة تقييم ومراجعة المشرف الأكاديمي.\n\n"
             "5. Issue #5: [TEST] خط أنابيب التكامل المستمر CI/CD والاختبارات.", COLOR_PRIMARY)

    add_card(s7, Inches(6.8), Inches(1.8), Inches(5.6), Inches(4.8),
             "دورة العمل المطبقة (Git Workflow per Issue)",
             "تطبيق الخطوات التسع المعتمدة في سلايد 13:\n\n"
             "1. Issue: توثيق المتطلب في قائمة المهام.\n"
             "2. Branch: إنشاء فرع مستقل (feature/*).\n"
             "3. Edit & Code: كتابة الكود النظيف في Laravel.\n"
             "4. Commit: توثيق رسالة واضحة بأسلوب Conventional Commits.\n"
             "5. Push: رفع الفرع لمستودع GitHub.\n"
             "6. Pull Request: فتح طلب دمج للمراجعة.\n"
             "7. Review & Test: تشغيل الـ CI والتأكد من نجاح الاختبارات.\n"
             "8. Merge: دمج الفرع المكتمل في main.\n"
             "9. Close Issue: إغلاق المهمة في لوحة Kanban.", COLOR_GREEN)

    # ----------------------------------------------------
    # SLIDE 8: CI/CD & Automated Testing
    # ----------------------------------------------------
    s8 = prs.slides.add_slide(blank_layout)
    add_background(s8)
    add_header(s8, "أتمتة الاختبارات والتكامل المستمر (CI/CD Pipeline)")

    add_card(s8, Inches(0.8), Inches(1.8), Inches(5.6), Inches(4.8),
             "GitHub Actions CI (.github/workflows/ci.yml)",
             "• يُشغل آلياً عند كل Push أو Pull Request لفرعي main و develop.\n\n"
             "• خطوات السير المؤتمتة:\n"
             "  1. تهيئة بيئة PHP 8.3 وتفعيل ملحقات pdo_sqlite و mbstring.\n"
             "  2. تثبيت حزم Composer عبر الكاش السريع.\n"
             "  3. توليد المفتاح وتجهيز قاعدة البيانات SQLite.\n"
             "  4. تشغيل الهجرات البرمجية (php artisan migrate).\n"
             "  5. تشغيل حزمة اختبارات PHPUnit (14 اختباراً ناجحاً بنسبة 100%).", COLOR_PRIMARY)

    add_card(s8, Inches(6.8), Inches(1.8), Inches(5.6), Inches(4.8),
             "حزمة الاختبارات البرمجية (14 Tests Passed)",
             "✔ AuthTest (5 اختبارات):\n"
             "  - ظهور صفحة الدخول، نجاح الدخول ببيانات صالحة، رفض كلمات المرور الخاطئة، وتسجيل الخروج، والتبديل السريع.\n\n"
             "✔ SubmissionAndReviewWorkflowTest (2 اختبارات):\n"
             "  - دورة التسليم كاملة، واعتماد المشرف واحتساب نسب الإنجاز.\n\n"
             "✔ EdgeCasesValidationTest (5 اختبارات):\n"
             "  - رفض الطالب غير المصرح له (403).\n"
             "  - رفض الروابط غير الصالحة (422).\n"
             "  - منع التسليم لمرحلة معتمدة.\n"
             "  - منع الطالب من تقييم نفسه (403).\n"
             "  - فحص حدود درجات التقييم (0 - 100).", COLOR_GREEN)

    # ----------------------------------------------------
    # SLIDE 9: AI Transparency (AI_Log.md)
    # ----------------------------------------------------
    s9 = prs.slides.add_slide(blank_layout)
    add_background(s9)
    add_header(s9, "الشفافية في استخدام الذكاء الاصطناعي (AI_Log.md)")

    add_card(s9, Inches(0.8), Inches(1.8), Inches(5.6), Inches(4.8),
             "الالتزام بتوجيهات م. ساهر الهمداني (سلايد 15)",
             "• القاعدة الذهبية: 'الذكاء الاصطناعي مساعد في التفكير، وليس بديلاً عن القرار الهندسي'.\n\n"
             "• الاستخدامات المشروعة المطبقة:\n"
             "  ✔ صياغة سيناريوهات حالات الحافة واختبارها.\n"
             "  ✔ تحسين صياغة قصص المستخدمين ومعايير Given/When/Then.\n"
             "  ✔ كتابة ملفات CI/CD و GitHub Actions.\n\n"
             "• ما تم تجنبه بصرامة:\n"
             "  ❌ لا يوجد نسخ أعمى دون فهم.\n"
             "  ❌ لم يتم إدراج أي كود لا أستطيع شرحه أثناء المناقشة.\n"
             "  ❌ عدم رفع أي بيانات حساسة أو مفاتيح سرية.", COLOR_ACCENT)

    add_card(s9, Inches(6.8), Inches(1.8), Inches(5.6), Inches(4.8),
             "ملف التوثيق الشفاف (AI_Log.md)",
             "جدول التوثيق يحتوي على 7 أعمدة معيارية:\n\n"
             "1. Date: تاريخ كل جلسة عمل.\n"
             "2. Student: إلياس جمال الساده.\n"
             "3. Tool: أداة الذكاء الاصطناعي (Antigravity AI / Gemini 3.8 Flash).\n"
             "4. Purpose: الغرض الهندسي المحدد.\n"
             "5. Accepted Suggestions: ما تم اعتماده بعد المراجعة.\n"
             "6. Rejected Suggestions: ما تم رفضه وتعديله بشرياً (مثل استبعاد مكتبات خارجية معقدة أو بوابات دفع غير مطلوبة).\n"
             "7. Human Review: القرار الهندسي والتحقق البشري.", COLOR_GREEN)

    # ----------------------------------------------------
    # SLIDE 10: Anticipated Defense Questions & Answers
    # ----------------------------------------------------
    s10 = prs.slides.add_slide(blank_layout)
    add_background(s10)
    add_header(s10, "الأسئلة المتوقعة في المناقشة مع المهندس ساهر وإجاباتها النموذجية")

    add_card(s10, Inches(0.8), Inches(1.8), Inches(5.6), Inches(4.8),
             "س1: كيف تضمن عدم قيام طالب بتسليم مشروع طالب آخر؟",
             "• الإجابة النموذجية:\n"
             "  'نطبق مبدأ Authorization Isolation في Laravel عبر FormRequest مخصص (StoreSubmissionRequest) داخل دالة authorize():\n\n"
             "  $milestone->project->isMember($user->id)\n\n"
             "  فإذا لم يكن المستخدم عضواً مسجلاً في جدول project_members لهذا المشروع، يرجع النظام فورياً كود 403 Forbidden، ومثبت ذلك في اختبار EdgeCasesValidationTest.'", COLOR_PRIMARY)

    add_card(s10, Inches(6.8), Inches(1.8), Inches(5.6), Inches(4.8),
             "س2: كيف تمنع عدم اتساق البيانات عند اعتماد مرحلة؟",
             "• الإجابة النموذجية:\n"
             "  'نستخدم المعاملات الذرية (Database Transactions):\n\n"
             "  DB::transaction(function() { ... });\n\n"
             "  حيث يتم إنشاء سجل المراجعة، وتحديث حالة المرحلة إلى Approved، وتحديث الدرجة، وإعادة احتساب نسبة إنجاز المشروع التراكمية في خطوة ذرية واحدة، فإذا فشلت أي خطوة يتم عمل Rollback تلقائي منعاً لأي خلل.'", COLOR_GREEN)

    # ----------------------------------------------------
    # SLIDE 11: Summary & Scorecard
    # ----------------------------------------------------
    s11 = prs.slides.add_slide(blank_layout)
    add_background(s11)
    add_header(s11, "خلاصة المخرجات وتحقيق معايير الـ 100 درجة")

    add_card(s11, Inches(0.8), Inches(1.8), Inches(11.6), Inches(4.8),
             "تغطية بنود التقييم كاملة (100 / 100)",
             "1. وضوح الفكرة وهدفها (10 درجات): منصة واقعية تخدم بيئة مشاريع التخرج الجامعية.\n"
             "2. جودة وثيقة Mini-SRS (20 درجة): مكتملة بملف docs/SRS.md متضمنة المشكلة والحل والنطاق.\n"
             "3. قصص المستخدمين ومعايير القبول (15 درجة): مصاغة بدقة بصيغة (Given / When / Then).\n"
             "4. معالجة حالات الحافة (10 درجات): 9 حالات حافة معالجة في الكود ومختبرة في معمل Edge Cases.\n"
             "5. إدارة المهام و GitHub Issues (10 درجات): 5 مهام موثقة بملف docs/KANBAN_BOARD.md.\n"
             "6. سير عمل Git Workflow (10 درجات): فروع ميزات، وفحص Pull Requests.\n"
             "7. التكامل المستمر CI/CD (10 درجات): 14 اختباراً آلياً مفعلاً في GitHub Actions.\n"
             "8. توثيق الذكاء الاصطناعي (5 درجات): ملف AI_Log.md وفق النموذج المعياري في سلايد 15.\n"
             "9. الفهم الهندسي والجاهزية للمناقشة (10 درجات): إلمام تام بكل ملف وسطر برمجي.\n\n"
             "• إعداد الطالب: إلياس جمال الساده | إشراف: المهندس ساهر الهمداني", COLOR_PRIMARY)

    output_path = r"c:\Users\Mk\Desktop\مشروع هندسة برمجيات عملي\GradTrack_Defense_Presentation.pptx"
    prs.save(output_path)
    print(f"Presentation created successfully at: {output_path}")

if __name__ == "__main__":
    create_defense_presentation()
