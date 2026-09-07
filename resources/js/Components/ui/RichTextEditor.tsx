import { useEffect } from 'react';
import { useEditor, EditorContent, type Editor } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';
import {
    Bold,
    Italic,
    Strikethrough,
    Heading2,
    Heading3,
    List,
    ListOrdered,
    Quote,
    Link as LinkIcon,
    Undo2,
    Redo2,
    Minus,
} from 'lucide-react';
import { cn } from '@/lib/utils';

interface Props {
    value: string;
    onChange: (html: string) => void;
    placeholder?: string;
    className?: string;
    minHeight?: number;
}

export function RichTextEditor({ value, onChange, placeholder, className, minHeight = 320 }: Props) {
    const editor = useEditor({
        extensions: [
            StarterKit.configure({
                heading: { levels: [2, 3] },
            }),
            Link.configure({
                openOnClick: false,
                autolink: true,
                HTMLAttributes: {
                    class: 'text-crimson underline underline-offset-2',
                    rel: 'noopener noreferrer',
                    target: '_blank',
                },
            }),
            Placeholder.configure({
                placeholder: placeholder ?? 'Rédige ton contenu…',
            }),
        ],
        content: value,
        onUpdate: ({ editor }) => {
            const html = editor.getHTML();
            onChange(html === '<p></p>' ? '' : html);
        },
        editorProps: {
            attributes: {
                class: cn(
                    'prose-editor max-w-none px-4 py-3 text-sm text-foreground focus:outline-none',
                    '[&_p]:my-3 [&_p:first-child]:mt-0 [&_p:last-child]:mb-0',
                    '[&_h2]:mt-6 [&_h2]:mb-3 [&_h2]:font-editorial [&_h2]:text-2xl [&_h2]:font-medium',
                    '[&_h3]:mt-5 [&_h3]:mb-2 [&_h3]:font-editorial [&_h3]:text-xl [&_h3]:font-medium',
                    '[&_ul]:my-3 [&_ul]:list-disc [&_ul]:pl-6',
                    '[&_ol]:my-3 [&_ol]:list-decimal [&_ol]:pl-6',
                    '[&_blockquote]:my-4 [&_blockquote]:border-l-2 [&_blockquote]:border-champagne/60 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-champagne',
                    '[&_a]:text-crimson [&_a]:underline',
                    '[&_hr]:my-6 [&_hr]:border-border',
                    '[&_p.is-editor-empty:first-child]:before:pointer-events-none [&_p.is-editor-empty:first-child]:before:float-left [&_p.is-editor-empty:first-child]:before:h-0 [&_p.is-editor-empty:first-child]:before:text-muted-foreground [&_p.is-editor-empty:first-child]:before:content-[attr(data-placeholder)]',
                ),
                style: `min-height: ${minHeight}px;`,
            },
        },
    });

    useEffect(() => {
        if (!editor) return;
        if (value !== editor.getHTML()) {
            editor.commands.setContent(value || '', { emitUpdate: false });
        }
    }, [editor, value]);

    if (!editor) return null;

    return (
        <div className={cn('overflow-hidden rounded-lg border border-input bg-card shadow-sm focus-within:border-crimson focus-within:ring-2 focus-within:ring-crimson/20', className)}>
            <Toolbar editor={editor} />
            <EditorContent editor={editor} />
        </div>
    );
}

function Toolbar({ editor }: { editor: Editor }) {
    const setLink = () => {
        const previous = editor.getAttributes('link').href ?? '';
        const url = window.prompt('URL du lien', previous);
        if (url === null) return;
        if (url === '') {
            editor.chain().focus().extendMarkRange('link').unsetLink().run();
            return;
        }
        editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
    };

    return (
        <div className="flex flex-wrap items-center gap-0.5 border-b border-border bg-muted/40 px-2 py-1.5">
            <ToolbarButton onClick={() => editor.chain().focus().toggleBold().run()} active={editor.isActive('bold')} label="Gras">
                <Bold className="h-4 w-4" />
            </ToolbarButton>
            <ToolbarButton onClick={() => editor.chain().focus().toggleItalic().run()} active={editor.isActive('italic')} label="Italique">
                <Italic className="h-4 w-4" />
            </ToolbarButton>
            <ToolbarButton onClick={() => editor.chain().focus().toggleStrike().run()} active={editor.isActive('strike')} label="Barré">
                <Strikethrough className="h-4 w-4" />
            </ToolbarButton>
            <Separator />
            <ToolbarButton onClick={() => editor.chain().focus().toggleHeading({ level: 2 }).run()} active={editor.isActive('heading', { level: 2 })} label="Titre 2">
                <Heading2 className="h-4 w-4" />
            </ToolbarButton>
            <ToolbarButton onClick={() => editor.chain().focus().toggleHeading({ level: 3 }).run()} active={editor.isActive('heading', { level: 3 })} label="Titre 3">
                <Heading3 className="h-4 w-4" />
            </ToolbarButton>
            <Separator />
            <ToolbarButton onClick={() => editor.chain().focus().toggleBulletList().run()} active={editor.isActive('bulletList')} label="Liste à puces">
                <List className="h-4 w-4" />
            </ToolbarButton>
            <ToolbarButton onClick={() => editor.chain().focus().toggleOrderedList().run()} active={editor.isActive('orderedList')} label="Liste numérotée">
                <ListOrdered className="h-4 w-4" />
            </ToolbarButton>
            <ToolbarButton onClick={() => editor.chain().focus().toggleBlockquote().run()} active={editor.isActive('blockquote')} label="Citation">
                <Quote className="h-4 w-4" />
            </ToolbarButton>
            <Separator />
            <ToolbarButton onClick={setLink} active={editor.isActive('link')} label="Lien">
                <LinkIcon className="h-4 w-4" />
            </ToolbarButton>
            <ToolbarButton onClick={() => editor.chain().focus().setHorizontalRule().run()} label="Séparateur">
                <Minus className="h-4 w-4" />
            </ToolbarButton>
            <div className="ml-auto flex items-center gap-0.5">
                <ToolbarButton onClick={() => editor.chain().focus().undo().run()} disabled={!editor.can().undo()} label="Annuler">
                    <Undo2 className="h-4 w-4" />
                </ToolbarButton>
                <ToolbarButton onClick={() => editor.chain().focus().redo().run()} disabled={!editor.can().redo()} label="Rétablir">
                    <Redo2 className="h-4 w-4" />
                </ToolbarButton>
            </div>
        </div>
    );
}

function ToolbarButton({
    onClick,
    active,
    disabled,
    label,
    children,
}: {
    onClick: () => void;
    active?: boolean;
    disabled?: boolean;
    label: string;
    children: React.ReactNode;
}) {
    return (
        <button
            type="button"
            onClick={onClick}
            disabled={disabled}
            aria-label={label}
            title={label}
            className={cn(
                'inline-flex h-8 w-8 items-center justify-center rounded-md text-muted-foreground transition-colors',
                'hover:bg-card hover:text-foreground',
                active && 'bg-card text-crimson',
                disabled && 'cursor-not-allowed opacity-40 hover:bg-transparent hover:text-muted-foreground',
            )}
        >
            {children}
        </button>
    );
}

function Separator() {
    return <div className="mx-1 h-5 w-px bg-border" />;
}
