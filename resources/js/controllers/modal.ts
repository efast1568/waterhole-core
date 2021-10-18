import { Controller } from '@hotwired/stimulus';
import { FrameElement } from '@hotwired/turbo/dist/types/elements';
import { ModalElement } from 'inclusive-elements';

export class ModalController extends Controller {
    static targets = ['loading', 'frame'];

    loadingTarget?: HTMLDivElement;
    frameTarget?: FrameElement;

    connect() {
        this.element.addEventListener('close', () => {
            this.frameTarget!.src = null;
        });
    }

    loading(e: any) {
        this.loadingTarget!.hidden = false;
        this.frameTarget!.hidden = true;
        this.show();
    }

    loaded() {
        this.loadingTarget!.hidden = true;
        this.frameTarget!.hidden = false;

        if (this.frameTarget?.children.length) {
            this.show();
        } else {
            this.hide();
        }
    }

    show() {
        if (! (this.element as ModalElement).open) {
            (this.element as ModalElement).open = true;
        }
    }

    hide(e?: Event) {
        e?.preventDefault();
        if ((this.element as ModalElement).open) {
            (this.element as ModalElement).open = false;
        }
    }
}