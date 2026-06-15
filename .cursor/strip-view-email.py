import re
import pathlib

root = pathlib.Path(__file__).resolve().parents[1] / "views"

patterns: list[tuple[re.Pattern[str], str]] = [
    (re.compile(r"\s*\|\|\s*\$data\['email'\]"), ""),
    (re.compile(r"\s*\|\|\s*\$data\['r_email'\]"), ""),
    (re.compile(r"\s*\|\|\s*\$fci_orders->is_status\('order-email'\)"), ""),
    (re.compile(r"^[ \t]*<\?php echo[^\n]*\$data\['email'\][^\n]*\?>\s*\n", re.M), ""),
    (re.compile(r"^[ \t]*<\?php echo[^\n]*\$data\['r_email'\][^\n]*\?>\s*\n", re.M), ""),
    (re.compile(r"^[ \t]*<p[^>]*>ایمیل:[^<]*<\?php echo[^\n]*\$data\['r_email'\][^\n]*</p>\s*\n", re.M), ""),
    (re.compile(r"^[ \t]*<p[^>]*>ایمیل:[^<]*<\?php echo[^\n]*\$data\['email'\][^\n]*</p>\s*\n", re.M), ""),
    (re.compile(r"^[ \t]*<\?php if \(\$data\['email'\]\) : \?>\s*\n[ \t]*<p[^\n]*</p>\s*\n[ \t]*<\?php endif; \?>\s*\n", re.M), ""),
    (re.compile(r"^[ \t]*<\?php if \(\$data\['r_email'\]\) : \?>\s*\n[ \t]*<p[^\n]*</p>\s*\n[ \t]*<\?php endif; \?>\s*\n", re.M), ""),
    (re.compile(r"^[ \t]*<\?php if \(  \$data\['r_email'\] \): \?>\s*\n.*?\n[ \t]*<\?php endif; \?>\s*\n", re.M | re.S), ""),
    (re.compile(r"^[ \t]*<\?php if \(  \$data\['email'\] \): \?>\s*\n.*?\n[ \t]*<\?php endif; \?>\s*\n", re.M | re.S), ""),
    (re.compile(r"^[ \t]*<\?php if \(\$fci_orders->is_status\('order-email'\)\): \?>\s*\n.*?\n[ \t]*<\?php endif; \?>\s*\n", re.M | re.S), ""),
    (re.compile(r"^[ \t]*'email' => \$shop->email_holder\(true\),\s*\n", re.M), ""),
    (re.compile(r"^[ \t]*'r_email' => \$customer->email_holder\(true\),\s*\n", re.M), ""),
]

for path in sorted(root.rglob("*.php")):
    text = path.read_text(encoding="utf-8")
    original = text
    for pattern, replacement in patterns:
        text = pattern.sub(replacement, text)
    if text != original:
        path.write_text(text, encoding="utf-8")
        print(path.relative_to(root.parent))
