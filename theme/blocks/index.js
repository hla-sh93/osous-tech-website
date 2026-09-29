/**
 * The editor side of every block, in one bundle.
 *
 * Each block's folder holds its block.json (what it is and what it stores), its
 * render.php (what it prints on the page) and its edit.js (how it is edited). This file
 * registers them with the editor; WordPress registers the same block.json files on the
 * server (inc/blocks.php), so both sides read the one definition.
 *
 * Blocks with children save their children's markup and nothing else -- the wrapper is
 * printed by render.php on the page. Blocks without children save nothing at all.
 */
import { InnerBlocks } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';

import './shared/formats';

import section from './section/block.json';
import SectionEdit from './section/edit';
import container from './container/block.json';
import ContainerEdit from './container/edit';
import grid from './grid/block.json';
import GridEdit from './grid/edit';
import column from './column/block.json';
import ColumnEdit from './column/edit';
import sectionHeading from './section-heading/block.json';
import SectionHeadingEdit from './section-heading/edit';
import stack from './stack/block.json';
import StackEdit from './stack/edit';
import group from './group/block.json';
import GroupEdit from './group/edit';
import panel from './panel/block.json';
import PanelEdit from './panel/edit';

import heading from './heading/block.json';
import HeadingEdit from './heading/edit';
import text from './text/block.json';
import TextEdit from './text/edit';
import image from './image/block.json';
import ImageEdit from './image/edit';
import button from './button/block.json';
import ButtonEdit from './button/edit';
import divider from './divider/block.json';
import DividerEdit from './divider/edit';
import spacer from './spacer/block.json';
import SpacerEdit from './spacer/edit';
import prose from './prose/block.json';
import ProseEdit from './prose/edit';
import list from './list/block.json';
import ListEdit from './list/edit';
import listItem from './list-item/block.json';
import ListItemEdit from './list-item/edit';

import card from './card/block.json';
import CardEdit from './card/edit';
import numberedCard from './numbered-card/block.json';
import NumberedCardEdit from './numbered-card/edit';
import highlightCard from './highlight-card/block.json';
import HighlightCardEdit from './highlight-card/edit';
import photoCard from './photo-card/block.json';
import PhotoCardEdit from './photo-card/edit';
import overlayCard from './overlay-card/block.json';
import OverlayCardEdit from './overlay-card/edit';
import featureCard from './feature-card/block.json';
import FeatureCardEdit from './feature-card/edit';
import partner from './partner/block.json';
import PartnerEdit from './partner/edit';
import pinnedItem from './pinned-item/block.json';
import PinnedItemEdit from './pinned-item/edit';

import pageHeader from './page-header/block.json';
import PageHeaderEdit from './page-header/edit';
import callToAction from './call-to-action/block.json';
import CallToActionEdit from './call-to-action/edit';
import pinnedList from './pinned-list/block.json';
import PinnedListEdit from './pinned-list/edit';
import pinnedItems from './pinned-items/block.json';
import PinnedItemsEdit from './pinned-items/edit';
import splitSection from './split-section/block.json';
import SplitSectionEdit from './split-section/edit';
import hero from './hero/block.json';
import HeroEdit from './hero/edit';
import cardStack from './card-stack/block.json';
import CardStackEdit from './card-stack/edit';
import industriesCarousel from './industries-carousel/block.json';
import IndustriesCarouselEdit from './industries-carousel/edit';
import partners from './partners/block.json';
import PartnersEdit from './partners/edit';
import contact from './contact/block.json';
import ContactEdit from './contact/edit';

const saveChildren = () => <InnerBlocks.Content />;
const saveNothing = () => null;

/**
 * Register one block.
 *
 * @param {Object}   metadata Its block.json.
 * @param {Function} edit     Its editor component.
 * @param {boolean}  children Whether it holds other blocks.
 */
function register( metadata, edit, children = false ) {
	registerBlockType( metadata.name, {
		...metadata,
		edit,
		save: children ? saveChildren : saveNothing,
	} );
}

// Layout.
register( section, SectionEdit, true );
register( container, ContainerEdit, true );
register( grid, GridEdit, true );
register( column, ColumnEdit, true );
register( sectionHeading, SectionHeadingEdit, true );
register( stack, StackEdit, true );
register( group, GroupEdit, true );
register( panel, PanelEdit, true );

// Content.
register( heading, HeadingEdit );
register( text, TextEdit );
register( image, ImageEdit );
register( button, ButtonEdit );
register( divider, DividerEdit );
register( spacer, SpacerEdit );
register( prose, ProseEdit, true );
register( list, ListEdit, true );
register( listItem, ListItemEdit );

// Cards.
register( card, CardEdit );
register( numberedCard, NumberedCardEdit, true );
register( highlightCard, HighlightCardEdit );
register( photoCard, PhotoCardEdit );
register( overlayCard, OverlayCardEdit );
register( featureCard, FeatureCardEdit );
register( partner, PartnerEdit );
register( pinnedItem, PinnedItemEdit );

// Whole sections.
register( pageHeader, PageHeaderEdit );
register( callToAction, CallToActionEdit );
register( pinnedList, PinnedListEdit, true );
register( pinnedItems, PinnedItemsEdit, true );
register( splitSection, SplitSectionEdit, true );
register( hero, HeroEdit, true );
register( cardStack, CardStackEdit, true );
register( industriesCarousel, IndustriesCarouselEdit, true );
register( partners, PartnersEdit, true );
register( contact, ContactEdit );
