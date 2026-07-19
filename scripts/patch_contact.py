from pathlib import Path
import re
link_pattern = re.compile(r'href="\{\{\s*url\(\'/contact\'\)\s*\}\}"')
updated_files = []
for path in Path('resources/views').rglob('*.blade.php'):
    text = path.read_text(encoding='utf-8')
    if not link_pattern.search(text):
        continue
    new_text = link_pattern.sub('href="#"', text)
    # add or extend contact-toggle class on anchors with href="#"
    def class_repl(match):
        attrs = match.group(1)
        classes = match.group(2)
        if 'contact-toggle' in classes.split():
            return match.group(0)
        return f'{attrs}class="{classes} contact-toggle"'
    new_text = re.sub(r'(<a[^>]*?href="#"[^>]*?)class="([^"]*)"', class_repl, new_text)
    # add class attr if missing on href="#" anchors
    def add_class(match):
        tag = match.group(1)
        if 'class=' in tag:
            return tag
        return f'{tag} class="contact-toggle"'
    new_text = re.sub(r'(<a[^>]*?href="#"(?![^>]*class=)[^>]*?)>', add_class, new_text)
    if new_text != text:
        path.write_text(new_text, encoding='utf-8')
        updated_files.append(str(path))
print('Updated', len(updated_files), 'files')
for f in updated_files:
    print(f)
