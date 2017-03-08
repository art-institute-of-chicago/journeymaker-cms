/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.dialog.add( 'annotate', function( editor )
{
	// Function called in onShow to load selected element.
	var loadElements = function( element )
	{
		this._.selectedElement = element;

		var attributeValue = element.data( 'cke-saved-name' );
		this.setValueOf( 'info','txtName', attributeValue || '' );
	};

	function createSpanWrap( editor, el )
	{
		return editor.createFakeElement( el, 'cke_annotate', 'span' );
	}

	return {
		title : 'Annotations',
		minWidth : 300,
		minHeight : 60,
		onOk : function()
		{
			var name = this.getValueOf( 'info', 'txtName' );
			var attributes =
			{
				name : name,
				'data-cke-saved-name' : name
			};

			if ( this._.selectedElement )
			{
				if ( this._.selectedElement.data( 'cke-realelement' ) )
				{
					var newFake = createSpanWrap( editor, editor.document.createElement( 'span', { attributes: attributes } ) );
					newFake.replace( this._.selectedElement );
				}
				else
					this._.selectedElement.setAttributes( attributes );
			}
			else
			{
				var sel = editor.getSelection(),
						range = sel && sel.getRanges()[ 0 ];

				// Empty anchor
				if ( range.collapsed )
				{
					if ( CKEDITOR.plugins.link.synAnchorSelector )
						attributes[ 'class' ] = 'cke_anchor_empty';

					if ( CKEDITOR.plugins.link.emptyAnchorFix )
					{
						attributes[ 'contenteditable' ] = 'false';
						attributes[ 'data-cke-editable' ] = 1;
					}

					var anchor = editor.document.createElement( 'a', { attributes: attributes } );

					// Transform the anchor into a fake element for browsers that need it.
					if ( CKEDITOR.plugins.link.fakeAnchor )
						anchor = createFakeAnchor( editor, anchor );

					range.insertNode( anchor );
				}
				else
				{
					if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 )
						attributes['class'] = 'cke_anchor';

					// Apply style.
					var style = new CKEDITOR.style( { element : 'a', attributes : attributes } );
					style.type = CKEDITOR.STYLE_INLINE;
					style.apply( editor.document );
				}
			}
		},

		onHide : function()
		{
			delete this._.selectedElement;
		},

		onShow : function()
		{
			var selection = editor.getSelection(),
				fullySelected = selection.getSelectedElement(),
				partialSelected;

			// Detect the anchor under selection.
			if ( fullySelected )
			{
				if ( CKEDITOR.plugins.link.fakeAnchor )
				{
					var realElement = CKEDITOR.plugins.link.tryRestoreFakeAnchor( editor, fullySelected );
					realElement && loadElements.call( this, realElement );
					this._.selectedElement = fullySelected;
				}
				else if ( fullySelected.is( 'a' ) && fullySelected.hasAttribute( 'name' ) )
					loadElements.call( this, fullySelected );
			}
			else
			{
				partialSelected = CKEDITOR.plugins.link.getSelectedLink( editor );
				if ( partialSelected )
				{
					loadElements.call( this, partialSelected );
					selection.selectElement( partialSelected );
				}
			}

			this.getContentElement( 'info', 'txtName' ).focus();
		},
		contents : [
			{
				id : 'info',
				label : 'General',
				accessKey : 'I',
				elements :
				[
					{
						type: 'select',
						id: 'annotationType',
						label: 'Type of Annotation',
						required: true,
						items: [
							['Glossary', 'glossary'],
							['Previous Work', 'previouswork'],
							['Author\'s Experiments', 'authorsexperiment'],
							['Conclusions', 'conclusion'],
							['News and Policy Links', 'newsandpolicylinks'],
							['Connect to Advanced Placement', 'connecttoadvancedplacement']
						],
						validate: function() {
							if ( !this.getValue() )
							{
								alert('Please select a type of annotation');
								return false;
							}
							return false;
						}
					},

					{
						type : 'textarea',
						id : 'annotation',
						label : 'Annotation',
						required: true,
						validate : function()
						{
							if ( !this.getValue() )
							{
								alert('Please enter text for the annotation');
								return false;
							}
							return true;
						}
					}
				]
			}
		]
	};
} );
